(() => {
  if (window && !window.EHDObject) {
    const identifier = 'EHDObject';
    const emptyObject = '{}';
    const jsonVal = window.sessionStorage.getItem(identifier) || emptyObject;
    const joiner = JSON.parse(jsonVal);
    // console.log("7 joiner ::" ,joiner);
    Object.defineProperties(joiner, {
      GET_AGE: { value: 'getAge', enumerable: false },
      GET_PLAN: { value: 'getPlans', enumerable: false },
      GET_SERVICE: { value: 'getService', enumerable: false },  
      GET_GUARANTEE: { value: 'getGuarantee', enumerable: false },
      GET_PLAN_PRICE: { value: 'getPlanPrice', enumerable: false },
      GET_ANOTHER_GUARANTEES: { value: 'getAnotherGuarantees', enumerable: false },
      GET_PRODUCT_COUNTRY: { value: 'getProductCountry', enumerable: false },
      GET_PARTNERSHIP: { value: 'getPartnership', enumerable: false },
      GET_NOTICE: { value: 'getNotice', enumerable: false },
      SERVICE_GROUP_NAME: { value: ['의료·여행편의 지원', '건강검진', '긴급이후송'], enumerable: false },
    //   SERVICE_GROUP_NAME: { value: ['이후송 서비스', '건강검진', '긴급이후송'], enumerable: false },
      PLAN_CD_NAME: { value: [, 'Lv1', 'Lv2', 'Lv3', 'Lv4', 'Lv5'], enumerable: false },
      NOT_AVAILABLE: { value: 'NOT-AVAILABLE', enumerable: false },
    });

    joiner.save = function () {
      window.sessionStorage.setItem(identifier, JSON.stringify(this));
    };

    joiner.remove = function () {
      window.sessionStorage.removeItem(identifier);
    };

    joiner.init = function () {
      for (const prop in this) {
        if (
          prop.indexOf('depth') === -1 &&
          prop.indexOf('selectedPartnership') === -1 &&
          typeof this[prop] !== 'function'
        )
          delete this[prop];
      }
    };

    joiner.cleaning = function () {
      for (const prop in this) {
        if (
          prop.indexOf('depth') === -1 &&
          prop.indexOf('companions') === -1 &&
          prop.indexOf('customer') === -1 &&
          prop.indexOf('selected') === -1 &&
          typeof this[prop] !== 'function'
        )
          delete this[prop];
      }
    };

    joiner.getCategories = function (pCode, callback) {
      const ajaxUrl = `/html/insurance/ajax_category.php?pCode=${pCode}`;
      fetch(ajaxUrl)
        .then((result) => result.json())
        .then(callback)
        .catch(console.log);
    };

    joiner.getServiceGroup = function (callback = () => null) {
      const ajaxUrl = `/html/insurance/ajax_planinfo.php?api=getServiceGroup`;
      fetch(ajaxUrl)
        .then((result) => result.json())
        .then(callback)
        .catch(console.log);
    };

    joiner.getPlanNames = function (callback) {
      let ajaxUrl = [];

      ajaxUrl.push(`depth0=${this.depth0?.code || ''}`);
      ajaxUrl.push(`depth1=${this.depth1?.code || ''}`);
      ajaxUrl.push(`depth2=${this.depth2?.code || ''}`);
      ajaxUrl.push(`depth3=${this.depth3?.code || ''}`);

      fetch(`/html/insurance/ajax_planName.php?${ajaxUrl.join('&')}`)
        .then((result) => result.json())
        .then(callback)
        .catch(console.log);
    };

    joiner.getAge = function (birth) {
      let age = 0;

      $.ajax({
        url: `/html/insurance/ajax_planInfo.php?api=${this.GET_AGE}&keyword=${birth}`,
        method: 'get',
        async: false,
        success: function (result) {
          age = Number(result);
        }
      });

      return age;
    };

    joiner.getPlanInfo = function (args, callback) {
  
      let apiName;
      const params = [];

      for (const key in args) {
        if (key === 'api') apiName = args[key];
        params.push(`${key}=${args[key]}`);
      }

    //   console.log("params",params);

      fetch(`/html/insurance/ajax_planInfo.php?${params.join('&')}`)
        .then((result) => result.json())
        .then((result) => {
            // console.log(apiName);
          if (apiName === this.GET_PLAN) {
            // console.log("GET_PLAN");
            // console.log(result);
            if (this.customer.departureDate && this.customer.arrivalDate && Array.isArray(result)) {
              const dptDate = this.customer.departureDate;
              this.plans = result.filter(
                (plan) => plan.s_date <= dptDate && dptDate <= plan.e_date && plan.plan_status == 'Y'
              );
              console.log(this.plans);
            } else {
              this.plans = result;
            }
          } else if (apiName === this.GET_GUARANTEE) this.guarantees = result;
          else if (apiName === this.GET_PLAN_PRICE) this.selectedPlanPrice = result;
          else if (apiName === this.GET_ANOTHER_GUARANTEES) this.anotherGuarantees = result;
          else if (apiName === this.GET_PRODUCT_COUNTRY) this.countries = result;
          else if (apiName === this.GET_PARTNERSHIP) this.selectedPartnership = result[0];
          else if (apiName === this.GET_NOTICE) this.productNotice = result;
          else if (apiName === this.GET_SERVICE) {
            // console.log("GET_SERVICE");
            // console.log(result)
            this.services = result;
            // 서비스 분류
            this.classifyServices();
          }
        //   console.log("===== "+this.GET_PLAN_PRICE+"====")
        //   console.log(result) 
          return result;
        })
        .then(callback)
        .catch(console.log);
    };

    joiner.createElement = function (tag, options, appendTo) {
      if (!tag) return undefined;
      const element = document.createElement(tag);

      if (options && typeof options === 'object' && !Array.isArray(options)) {
        for (const key in options) {
          const option = options[key];
          switch (key) {
            case 'attribute':
              if (option) {
                for (const name in option) element.setAttribute(name, option[name]);
              }
              break;
            case 'classList':
              if (Array.isArray(option)) option.forEach((cls) => element.classList.add(cls));
              else element.classList.add(option);
              break;
            case 'text':
              element.textContent = option || '';
              break;
            case 'child':
              if (Array.isArray(option))
                option.forEach((child) => this.createElement(child.tag, child.options, element));
              else this.createElement(option.tag, option.options, element);
              break;
          }
        }
      }

      if (appendTo) {
        appendTo.append(element);
        return appendTo;
      }

      return element;
    };

    joiner.classifyServices = function () {
        // console.log(this.services)
      const planSeq = [];
      const container = [];
      const NOT_AVAILABLE = this.NOT_AVAILABLE;
      const dataList = this.services?.filter((s) => s.e_amount !== NOT_AVAILABLE || s.k_amount !== NOT_AVAILABLE) || [];
        // console.log(dataList);
      // 서비스 목록 중 중복되지 않은 plan_seq 값 수집
      dataList.forEach((item) => planSeq.includes(item.plan_seq) || planSeq.push(item.plan_seq));
        // console.log("planSeq ::" , planSeq);
      // 서비스 목록에서 공통서비스와 옵션 서비스 분류
      // 서비스이름, 서비스값 을 키로 임의 배열에 등록하고, 이미 등록된 서비스인 경우 group_count 를 증가시킴
      // 서비스별 group_count 값이 planSeq.length 와 같으면 공통서비스, 다르면 옵션서비스로 판단
      dataList.forEach((service) => {
        let s = container.find((c) => c.service_name === service.service_name && c.k_amount === service.k_amount);
        if (s) s.group_count += 1;
        else container.push({ ...service, group_count: 1 });
      });

      // 공통서비스 목록
      const commonServices = container.filter((s) => s.service_group_name === this.SERVICE_GROUP_NAME[0]);
      // 옵션서비스 목록
      const optionServices = container.filter((s) => s.service_group_name !== this.SERVICE_GROUP_NAME[0]);

      this.commonServices = commonServices;
      this.optionServices = optionServices;
    };

    /**
     * 장기상품인경우 기간을 개월수로 구하여 요금테이블 참조
     * 단기상품의경우 기간을 일수로 구하여 요금테이블 참조
     * @param {*} param0
     * @returns
     */
    joiner.calculatePriceByPerson = function ({ dayPeriod, monthPeriod, age, gender }) {
      let totalPrice = 0;
      let gPrice = 0;
      let sPrice = 0;
      let shortTable = JSON.parse(`[[0, 0],[0, 2],[2, 3],[3, 4],[4, 5],[5, 6],[6, 7],[7, 10],[10, 14],
        [14, 17],[17, 21],[21, 24],[24, 27],[27, 30],[30, 45],[45, 60],[60, 90]]`);
      let price = {
        totalPrice,
        gPrice,
        sPrice,
      };
      //console.log('220 price :: ',price);
      if (!this.selectedPlan) {
       
        //console.log('222 price :: ',price);
        return price;
      } else if (!monthPeriod || !dayPeriod || !age || !gender) {
        return price;
      } else {
        let idx = 1;
        let gPriceRow;
        let sPriceRow;
        const gStr = gender === 'M' ? '남자' : '여자';

        if (this.isLongterm() === 1) {
          // 장기 플랜
          idx = monthPeriod;
        } else if (this.isLongterm() === 0) {
          // 단기 플랜
          idx = shortTable.findIndex((t) => t[0] < dayPeriod && t[1] >= dayPeriod);
        } else return price;

        // 선택된 플랜에 보험이 포함된경우 보험료 계산
        if (this.selectedPlan.ext1 === 'Y' || this.selectedPlan.ext2 === 'Y') {
          gPriceRow = this.selectedPlanPrice.find((p) => p.age == age && p.gender == gStr && p.plan_type == 'G');
          gPrice = gPriceRow ? Number(gPriceRow[`period${idx}`]) || 0 : 0;
        }

        // 선택된 플랜에 서비스가 포함된경우 서비스료 계산
        if (this.selectedPlan.ext3 === 'Y') {
          sPriceRow = this.selectedPlanPrice.find((p) => p.plan_type == 'S');
          if (this.selectedPlan && this.selectedPlan.service_amount_per_day > 0) {
            // 서비스의 경우 일단위 요금이 존재 한다면 일단위 요금으로 계산
            sPrice = Number(this.selectedPlan.service_amount_per_day) * dayPeriod;
          } else {
            sPriceRow = this.selectedPlanPrice.find((p) => p.age == age && p.gender == gStr && p.plan_type == 'S');
            sPrice = sPriceRow ? Number(sPriceRow[`period${idx}`]) || 0 : 0;
          }
        }
      }

      return { totalPrice: gPrice + sPrice, gPrice, sPrice };
    };

    /**
     * @returns 1 (longterm), 0 (shorterm), -1 (unknown)
     */
    joiner.isLongterm = function () {
      return this.depth1 ? (this.depth1.name === '장기' ? 1 : 0) : -1;
    };

    joiner.checkPartnership = function (callback) {
      const param = location.search.match(/alliance_code=[^&]*/);
      if (param) {
        const arr = param[0].split('=');
        this.getPlanInfo({ api: this.GET_PARTNERSHIP, keyword: arr[1] }, callback);
      } else if (typeof callback === 'function') callback();
    };

    joiner.getFormatedDate = function (date) {
      const p = (data) => String(data).padStart(2, 0);
      return `${date.getFullYear()}-${p(date.getMonth() + 1)}-${p(date.getDate())}`;
    };

    window.EHDObject = joiner;
    document.addEventListener('DOMContentLoaded', () => {
      EHDObject.checkPartnership(() => {
        EHDObject.save();
      });
    });
 

    // console.log('EHDObject :: ',EHDObject);

  }
})();
