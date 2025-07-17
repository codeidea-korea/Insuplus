


document.addEventListener('keydown', function(e) {
    // Ctrl+P (프린트)
    if (e.ctrlKey && (e.key === 'p' || e.keyCode === 80)) {
      e.preventDefault();
      return false;
    }
    
    // Ctrl+Shift+I, F12 (개발자 도구)
    if ((e.ctrlKey && e.shiftKey && (e.key === 'i' || e.keyCode === 73)) || 
        (e.key === 'F12' || e.keyCode === 123)) {
      e.preventDefault();
      return false;
    }
    
    // Ctrl+S (저장)
    if (e.ctrlKey && (e.key === 's' || e.keyCode === 83)) {
      e.preventDefault();
      return false;
    }
  }, false);
  
  // 복사 방지
  document.addEventListener('copy', function(e) {
   
    e.preventDefault();
    return false;
  }, false);
  
  // 페이지를 떠날 때 캔버스를 비워서 스크린샷 방지 시도 
  window.addEventListener('beforeunload', function() {
    document.body.innerHTML = '';
  });

  document.addEventListener('contextmenu', function (e) {
    
    e.preventDefault(); // 우클릭 메뉴 차단
    alert('개인정보 보호를 위해 오른쪽 클릭 및 복사 기능이 제한됩니다.');
  });