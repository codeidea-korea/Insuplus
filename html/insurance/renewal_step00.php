<?php
include '../_include/_header_new.html';
include '../_include/_top.html';

$_SESSION["orderno"] = "";
?>
<section>
  <div class="container">
    <div class="title-box">
      <h2>간편조회</h2>
      <p>해외여행기간을 선택해 주세요.</p>
    </div>
    <div class="check-link-box">
      <ul>
        <li>
          <a href="">
            <strong>90일 미만</strong>
            <p>해외여행, 출장, 어학연수 등</p>
            <span>간편 조회 시작</span>
          </a>
        </li>
        <li>
          <a href="">
            <strong>90일 이상</strong>
            <p>유학, 워킹홀리데이, 해외근무 등</p>
            <span>간편 조회 시작</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- 10월 2일부터 31일 이벤트
<div class="check-link-box">
<ul>
<li>
<a href="">
<strong>90일 미만</strong>
<p>해외여행, 출장, 어학연수 등</p><br></br>
<p>★ 10월 한정 서비스료 10% 할인 ★</p>
<span>간편 조회 시작</span>
</a>
</li>
<li>
<a href="">
<strong>90일 이상</strong>
<br></br><p>유학, 워킹홀리데이, 해외근무 등</p><br></br>
<span>간편 조회 시작</span>
</a>
</li>
</ul>
</div> -->
  </div>
</section>
<script src="./js/swiper.js"></script>
<script src="./js/ehd-object.js"></script>
<script>
  function clickEventHandler(element, {
    category_code,
    category_name
  }) {
    const nextUrl = `renewal_step01.php`;
    element.addEventListener('click', (e) => {
      e.preventDefault();
      EHDObject.depth1 = {
        code: category_code,
        name: category_name
      };
      EHDObject.depth2 = undefined;
      EHDObject.depth3 = undefined;
      EHDObject.save();
      location.href = nextUrl;
    });
  }

  function addEventOnDepth1(dataList) {
    if (Array.isArray(dataList) && dataList.length > 0) {
      document.querySelectorAll('section a').forEach((el) => {
        const strongEl = el.querySelector('strong');
        if (strongEl && strongEl.textContent.indexOf('이상') > -1) {
          const code = dataList.find((el) => el.category_name && el.category_name.includes('장기'));
          if (code) {
            clickEventHandler(el, code);
          }
        } else if (strongEl && strongEl.textContent.indexOf('미만') > -1) {
          const code = dataList.find((el) => el.category_name && el.category_name.includes('단기'));
          if (code) {
            clickEventHandler(el, code);
          }
        }
      });
    }
  }


  window.addEventListener('load', () => {
    const partner_type = '<?= $PARTNER_TYPE ?>';
    EHDObject.init();
    if (partner_type.length > 0 && partner_type != 'undefined') {
      EHDObject.depth0 = {
        code: 'C001',
        name: '국내상품'
      };
      EHDObject.depth1 = undefined;
      EHDObject.depth2 = undefined;
      EHDObject.depth3 = undefined;
      EHDObject.save();
      EHDObject.getCategories(EHDObject.depth0.code, addEventOnDepth1);
    } else {
      if (EHDObject.selectedPartnership) {
        const ps = EHDObject.selectedPartnership;
        EHDObject.depth0 = {
          code: ps.partnership_category_code,
          name: ps.partnership_name
        };
        EHDObject.save();
        EHDObject.getCategories(EHDObject.depth0.code, addEventOnDepth1);
      } else {
        EHDObject.checkPartnership(() => {
          const param = location.search.match(/alliance_code=[^&]*/);
          if (param && !EHDObject.selectedPartnership) {
            alert('등록되지 않은 제휴사 코드 입니다.');
            location.href = './renewal_step00.php';
            return;
          } else if (param && EHDObject.selectedPartnership) {
            const ps = EHDObject.selectedPartnership;
            EHDObject.depth0 = {
              code: ps.partnership_category_code,
              name: ps.partnership_name
            };
            EHDObject.save();
            EHDObject.getCategories(EHDObject.depth0.code, addEventOnDepth1);
          } else {
            EHDObject.depth0 = {
              code: 'C001',
              name: '국내상품'
            };
            EHDObject.depth1 = undefined;
            EHDObject.depth2 = undefined;
            EHDObject.depth3 = undefined;
          }
          EHDObject.save();
          EHDObject.getCategories(EHDObject.depth0.code, addEventOnDepth1);
        });
      }
    }
  });
</script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>