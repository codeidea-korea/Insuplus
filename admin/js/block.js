document.addEventListener('keydown', function(e) {
    // Print Screen 키들 (스크린샷 방지) - 모든 가능한 경우
    if (e.key === 'PrintScreen' || 
        e.key === 'Print' ||
        e.key === 'Snapshot' ||
        e.key === 'PrtSc' ||
        e.key === 'PrtScr' ||
        e.code === 'PrintScreen' ||
        e.code === 'Snapshot' ||
        e.code === 'Print' ||
        e.code === 'PrtSc' ||
        e.code === 'PrtScr' ||
        e.keyCode === 44 || 
        e.keyCode === 144 ||
        e.keyCode === 42 ||
        e.keyCode === 124 ||
        e.which === 44 ||
        e.which === 144 ||
        e.which === 42 ||
        e.which === 124) {
        
        // 즉시 차단 처리
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        // 클립보드 조작 (스크린샷 이미지 덮어쓰기)
        stopPrntScr();
        
        // 즉시 방어 실행
        hideContentImmediately();
        showWatermarkImmediately();
        showWarning();
        
        return false;
    }

    // Windows + Shift + S (Windows 스크린샷 도구)
    if ((e.metaKey || e.key === 'Meta') && e.shiftKey && (e.key === 's' || e.key === 'S' || e.keyCode === 83)) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        // 클립보드 조작
        stopPrntScr();
        
        hideContentImmediately();
        showWatermarkImmediately();
        showWarning();
        return false;
    }

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
    
    // Ctrl+U (소스보기)
    if (e.ctrlKey && (e.key === 'u' || e.keyCode === 85)) {
        e.preventDefault();
        return false;
    }
}, true);

// keypress로도 즉시 차단
document.addEventListener('keypress', function(e) {
    if (e.key === 'PrintScreen' || e.keyCode === 44 || e.which === 44) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        stopPrntScr(); // 클립보드 조작 추가
        hideContentImmediately();
        showWatermarkImmediately();
        showWarning();
        return false;
    }
}, true);

// keyup으로도 즉시 차단 + 클립보드 조작
document.addEventListener('keyup', function(e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    
    // Print Screen 키코드 44 우선 체크 (클립보드 조작용)
    if (keyCode == 44) {
        stopPrntScr();
    }
    
    if (e.key === 'PrintScreen' || 
        e.key === 'Print' ||
        e.key === 'Snapshot' ||
        e.key === 'PrtSc' ||
        e.key === 'PrtScr' ||
        e.code === 'PrintScreen' ||
        e.code === 'Snapshot' ||
        e.code === 'Print' ||
        e.code === 'PrtSc' ||
        e.code === 'PrtScr' ||
        e.keyCode === 44 || 
        e.keyCode === 144 ||
        e.keyCode === 42 ||
        e.keyCode === 124 ||
        e.which === 44 ||
        e.which === 144 ||
        e.which === 42 ||
        e.which === 124) {
        
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        // 클립보드 조작
        stopPrntScr();
        
        // 즉시 방어 실행
        hideContentImmediately();
        showWatermarkImmediately();
        showWarning();
        
        return false;
    }
}, true);

// 복사 방지
document.addEventListener('copy', function(e) {
    e.preventDefault();
    return false;
}, false);

// 우클릭 방지
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    alert('개인정보 보호를 위해 오른쪽 클릭 및 복사 기능이 제한됩니다.');
});

// 페이지 포커스 변화 감지 (스크린샷 도구 사용 시)
let isPageActive = true;

window.addEventListener('blur', function() {
    isPageActive = false;
    hideContentImmediately();
    showWatermarkImmediately();
    showWarning();
});

window.addEventListener('focus', function() {
    isPageActive = true;
    setTimeout(function() {
        showContent();
        hideWatermark();
        hideWarning();
    }, 800);
});

// 브라우저 창 크기 변화 감지 (개발자 도구 감지)
let devtools = false;
setInterval(function() {
    if (window.outerHeight - window.innerHeight > 200 || 
        window.outerWidth - window.innerWidth > 200) {
        if (!devtools) {
            devtools = true;
            hideContent();
            alert('개발자 도구가 감지되었습니다.');
        }
    } else {
        devtools = false;
        if (isPageActive) {
            showContent();
        }
    }
}, 500);

// 주기적으로 페이지 포커스 상태 확인
setInterval(function() {
    if (!document.hasFocus()) {
        hideContent();
    }
}, 100);

// 텍스트 선택 방지
document.onselectstart = function() {
    return false;
};

document.ondragstart = function() {
    return false;
};

// 이미지 드래그 방지
document.addEventListener('dragstart', function(e) {
    e.preventDefault();
});

// 페이지를 떠날 때 캔버스를 비워서 스크린샷 방지 시도 
window.addEventListener('beforeunload', function() {
    // document.body.innerHTML = '';
});

// Print Screen 클립보드 조작 함수
function stopPrntScr() {
    try {
        var inpFld = document.createElement("input");
        inpFld.setAttribute("value", "Access Restricted - Screenshot Blocked");
        inpFld.setAttribute("width", "0");
        inpFld.style.height = "0px";
        inpFld.style.width = "0px";
        inpFld.style.border = "0px";
        inpFld.style.position = "absolute";
        inpFld.style.left = "-9999px";
        inpFld.style.opacity = "0";
        document.body.appendChild(inpFld);
        inpFld.select();
        document.execCommand("copy");
        inpFld.remove();
        
        // 추가 클립보드 조작 (IE/Legacy 지원)
        AccessClipboardData();
    } catch (err) {
        console.log("클립보드 조작 실패:", err);
    }
}

// 클립보드 데이터 접근 함수 (IE/Legacy 브라우저용)
function AccessClipboardData() {
    try {
        if (window.clipboardData && window.clipboardData.setData) {
            window.clipboardData.setData('text', "Access Restricted - Screenshot Blocked");
        }
    } catch (err) {
        console.log("Legacy 클립보드 조작 실패:", err);
    }
}

// 콘텐츠 숨기기/보이기 함수들 (즉시 실행 버전 추가)
let isContentProtected = false; // 보호 상태 추적

function hideContentImmediately() {
    isContentProtected = true;
    const bodyElements = document.querySelectorAll('body *:not(.watermark):not(#warning)');
    bodyElements.forEach(function(el) {
        el.style.filter = 'blur(20px)';
        el.style.opacity = '0.1';
        el.style.transition = 'none'; // 트랜지션 제거로 즉시 적용
        el.style.pointerEvents = 'none'; // 클릭 비활성화
        el.style.userSelect = 'none'; // 선택 비활성화
    });
    
    // 전체 body에도 클릭 방지 적용
    document.body.style.pointerEvents = 'none';
    
    // 경고창과 워터마크만 클릭 가능하도록 설정
    const warning = document.getElementById('warning');
    const watermark = document.querySelector('.watermark');
    if (warning) warning.style.pointerEvents = 'auto';
    if (watermark) watermark.style.pointerEvents = 'none';
}

function hideContent() {
    isContentProtected = true;
    const bodyElements = document.querySelectorAll('body *:not(.watermark):not(#warning)');
    bodyElements.forEach(function(el) {
        el.style.filter = 'blur(20px)';
        el.style.opacity = '0.1';
        el.style.pointerEvents = 'none'; // 클릭 비활성화
        el.style.userSelect = 'none'; // 선택 비활성화
    });
    
    // 전체 body에도 클릭 방지 적용
    document.body.style.pointerEvents = 'none';
    
    // 경고창만 클릭 가능하도록 설정
    const warning = document.getElementById('warning');
    if (warning) warning.style.pointerEvents = 'auto';
}

function showContent() {
    isContentProtected = false;
    const bodyElements = document.querySelectorAll('body *:not(.watermark):not(#warning)');
    bodyElements.forEach(function(el) {
        el.style.filter = 'none';
        el.style.opacity = '1';
        el.style.pointerEvents = 'auto'; // 클릭 활성화
        el.style.userSelect = 'auto'; // 선택 활성화
    });
    
    // body 클릭 방지 해제
    document.body.style.pointerEvents = 'auto';
}

function showWarning() {
    // 경고 요소가 없으면 동적으로 생성
    let warning = document.getElementById('warning');
    if (!warning) {
        // warning = document.createElement('div');
        // warning.id = 'warning';
        // warning.innerHTML = '개인정보 보호를 위해 콘텐츠가 보호됩니다.';
        // warning.style.cssText = `
        //     position: fixed;
        //     top: 50%;
        //     left: 50%;
        //     transform: translate(-50%, -50%);
        //     background: red;
        //     color: white;
        //     padding: 20px;
        //     border-radius: 8px;
        //     font-size: 18px;
        //     font-weight: bold;
        //     z-index: 10001;
        //     text-align: center;
        //     filter: none !important;
        //     opacity: 1 !important;
        // `;
        document.body.appendChild(warning);
    }
    warning.style.display = 'block';
}

function hideWarning() {
    const warning = document.getElementById('warning');
    if (warning) {
        warning.style.display = 'none';
    }
}

// 워터마크 동적 생성
function createWatermark() {
    const watermark = document.createElement('div');
    watermark.className = 'watermark';
    watermark.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 9999;
  
        display: none;
    `;
    document.body.appendChild(watermark);
}

function showWatermarkImmediately() {
    const watermark = document.querySelector('.watermark');
    if (watermark) {
        watermark.style.display = 'block';
        watermark.style.zIndex = '9999';
        watermark.style.transition = 'none'; // 즉시 표시
    }
}

function showWatermark() {
    const watermark = document.querySelector('.watermark');
    if (watermark) {
        watermark.style.display = 'block';
        watermark.style.zIndex = '9999';
    }
}

function hideWatermark() {
    const watermark = document.querySelector('.watermark');
    if (watermark) {
        watermark.style.display = 'none';
    }
}

// 페이지 로드 시 워터마크 생성 (비활성화 상태로)
document.addEventListener('DOMContentLoaded', function() {
    createWatermark();
    
    // 사용자 선택 방지 CSS 추가
    const style = document.createElement('style');
    style.textContent = `
        * {
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }
    `;
    document.head.appendChild(style);
});

// // === 붙여넣기 완전 차단(허용: data-allow-paste 있는 요소만) ===
// (function () {
//     const SEL = 'input:not([data-allow-paste]), textarea:not([data-allow-paste]), [contenteditable]:not([data-allow-paste])';
  
//     function isField(el) {
//       return el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement || (el && el.isContentEditable);
//     }
  
//     function attach(el) {
//       if (!isField(el) || el.__noPasteBound) return;
//       el.__noPasteBound = true;
  
//       const block = (e) => {
//         // 붙여넣기/드롭/단축키/컨텍스트메뉴 전부 차단
//         if (e.cancelable) e.preventDefault();
//         e.stopPropagation();
//         if (e.stopImmediatePropagation) e.stopImmediatePropagation();
//         return false;
//       };
  
//       // 1) 기본 paste
//       el.addEventListener('paste', block, { capture: true });
  
//       // 2) 최신 브라우저: beforeinput에서 paste/drop 유형 차단
//       el.addEventListener('beforeinput', (e) => {
//         const t = e.inputType;
//         if (t === 'insertFromPaste' || t === 'insertFromPasteAsQuotation' || t === 'insertFromDrop') {
//           if (e.cancelable) e.preventDefault();
//           e.stopPropagation();
//           if (e.stopImmediatePropagation) e.stopImmediatePropagation();
//         }
//       }, { capture: true });
  
//       // 3) 드래그 드롭 차단
//       el.addEventListener('drop', block, { capture: true });
//       el.addEventListener('dragover', block, { capture: true });
  
//       // 4) 단축키(Ctrl/Cmd+V, Shift+Insert) 차단
//       el.addEventListener('keydown', (e) => {
//         const k = (e.key || '').toLowerCase();
//         if ((e.ctrlKey || e.metaKey) && k === 'v') return block(e);
//         if (e.shiftKey && k === 'insert') return block(e);
//       }, { capture: true });
  
//       // 5) 컨텍스트 메뉴로 붙여넣기 유도 차단(선택)
//       el.addEventListener('contextmenu', block, { capture: true });
  
//       // 6) 일부 브라우저/구형 대응: 인라인 핸들러도 강제
//       el.onpaste = block;
  
//       // 7) 혹시 통과되면 즉시 원복 (한 프레임 뒤 확인)
//       el.addEventListener('input', (e) => {
//         // 붙여넣기 뒤에 값이 한번에 많이 늘어나는 경우만 롤백 (간단 휴리스틱)
//         const val = (el.value ?? el.textContent ?? '') + '';
//         if (val.length > (el.__prevLen || 0) + 1) {
//           if ('value' in el) el.value = el.__prevVal || '';
//           else el.textContent = el.__prevVal || '';
//           try {
//             const pos = ('value' in el ? el.value.length : (el.textContent || '').length);
//             el.setSelectionRange && el.setSelectionRange(pos, pos);
//           } catch (_) {}
//         }
//         el.__prevVal = ('value' in el) ? el.value : (el.textContent || '');
//         el.__prevLen = (el.__prevVal || '').length;
//       }, { capture: true });
  
//       // 초기 값 저장
//       el.__prevVal = ('value' in el) ? el.value : (el.textContent || '');
//       el.__prevLen = (el.__prevVal || '').length;
//     }
  
//     // 초기 바인딩
//     document.querySelectorAll(SEL).forEach(attach);
  
//     // 동적 추가/ShadowRoot 내부까지 추적
//     const mo = new MutationObserver((muts) => {
//       for (const m of muts) {
//         m.addedNodes.forEach((n) => {
//           if (n.nodeType !== 1) return;
//           if (n.matches && n.matches(SEL)) attach(n);
//           n.querySelectorAll && n.querySelectorAll(SEL).forEach(attach);
//           // Shadow DOM 내부
//           if (n.shadowRoot) {
//             n.shadowRoot.querySelectorAll(SEL).forEach(attach);
//           }
//         });
//       }
//     });
//     mo.observe(document.documentElement, { subtree: true, childList: true });
  
//     // iframe(동일 출처) 내부 처리
//     function bindInIframes() {
//       document.querySelectorAll('iframe').forEach((f) => {
//         try {
//           const d = f.contentDocument;
//           if (!d || d.__noPasteIframeBound) return;
//           d.__noPasteIframeBound = true;
//           d.querySelectorAll(SEL).forEach(attach);
//           new MutationObserver((muts) => {
//             for (const m of muts) {
//               m.addedNodes.forEach((n) => {
//                 if (n.nodeType !== 1) return;
//                 if (n.matches && n.matches(SEL)) attach(n);
//                 n.querySelectorAll && n.querySelectorAll(SEL).forEach(attach);
//               });
//             }
//           }).observe(d.documentElement, { subtree: true, childList: true });
//         } catch (_) { /* cross-origin이면 접근 불가 */ }
//       });
//     }
//     bindInIframes();
//     window.addEventListener('load', bindInIframes, { once: true });
//   })();
  