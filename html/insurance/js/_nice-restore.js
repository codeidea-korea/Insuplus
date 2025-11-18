function backupFormData() {
    const formData = {
        // 기본 정보
        birth: $('#A-birth').val(),
        gender: $('input[name="A-gender"]:checked').val(),

        // 여행 정보
        departure: $('#A-departure').val(),
        departureTime: $('#A-departure-time').val(),
        arrival: $('#A-arrival').val(),
        arrivalTime: $('#A-arrival-time').val(),

        // 동반인 정보
        companionCount: $('#B-count').val(),
        companions: [],
        bAgree: $('#B-agree').prop('checked'),

        // 상품 선택
        depth2: $('input[name="depth2"]:checked').val(),
        depth2Name: $('input[name="depth2"]:checked').data('name'),
        depth3: $('input[name="depth3"]:checked').val(),
        depth3Name: $('input[name="depth3"]:checked').data('name'),

        // 연령 확인
        agecheck: $('#agecheck').val(),

        
        // 타임스탬프
        timestamp: new Date().getTime()
    };

    // 동반인 정보 수집
    const companionCount = Number($('#B-count').val() || 0);
    for (let i = 0; i < companionCount; i++) {
        formData.companions.push({
            birth: $(`#B-birth-${i}`).val(),
            gender: $(`input[name="B-gender-${i}"]:checked`).val()
        });
    }

    // sessionStorage에 저장
    sessionStorage.setItem('nice_form_backup', JSON.stringify(formData));
    console.log('폼 데이터 백업 완료:', formData);
}
// ========== 폼 데이터 복원 (전체) ==========
function restoreFormData() {
    const backup = sessionStorage.getItem('nice_form_backup');
    console.log('=== 폼 복원 시작 ===');
    
    if (!backup) {
        console.log('백업 데이터 없음');
        return;
    }

    try {
        const formData = JSON.parse(backup);

        // 타임스탬프 체크
        const now = new Date().getTime();
        const diff = now - formData.timestamp;
        if (diff > 30 * 60 * 1000) {
            console.log('백업 데이터 만료');
            sessionStorage.removeItem('nice_form_backup');
            return;
        }

        console.log('복원할 데이터:', formData);

        // 1. 기본 정보 복원
        if (formData.birth) {
            document.getElementById('A-birth').value = formData.birth;
        }
        
        if (formData.gender) {
            const genderRadio = document.querySelector(`input[name="A-gender"][value="${formData.gender}"]`);
            if (genderRadio) genderRadio.checked = true;
        }

        // 2. 상품 선택 복원
        if (formData.depth2) {
            restoreDepth2AndDepth3(formData, () => {
                // 3. 여행 정보 복원
                restoreTravelInfo(formData);
                
                // 4. 동반인 정보 복원
                restoreCompanions(formData);
                
                // 5. 연령 확인 복원 (NICE 인증 여부에 따라 처리)
                restoreAgeCheck(formData);
                
                console.log('=== 🎉 폼 복원 완료 ===');
            });
        } else {
            restoreTravelInfo(formData);
            restoreCompanions(formData);
            restoreAgeCheck(formData);
            
            console.log('=== 🎉 폼 복원 완료 ===');
        }
        sessionStorage.removeItem('nice_form_backup');

    } catch (e) {
        console.error('❌ 폼 복원 실패:', e);
        sessionStorage.removeItem('nice_form_backup');
    }
}

function restoreAgeCheck(formData) {
    if (!formData.agecheck) return;
    
    setTimeout(() => {
        const ageCheckSelect = document.getElementById('agecheck');
        const isNiceAuthCompleted = sessionStorage.getItem('nice_auth_completed') === 'true';
        
        console.log('연령 확인 복원:', formData.agecheck);
        console.log('NICE 인증 완료 여부:', isNiceAuthCompleted);
        
        // 값만 설정 (이벤트 트리거 안 함)
        ageCheckSelect.value = formData.agecheck;
        
        if (isNiceAuthCompleted) {
            // NICE 인증이 완료된 경우 - ageCheck 함수 호출하지 않음
            console.log('✅ NICE 인증 완료 상태 유지');
            
            // UI 강제 적용
            applyNiceAuthUI();
        } else {
            // NICE 인증이 안 된 경우 - ageCheck 함수 호출
            console.log('✅ 연령 확인 함수 호출');
            ageCheck(ageCheckSelect);
        }
    }, 1200);
}

// ========== depth2, depth3 복원 ==========
function restoreDepth2AndDepth3(formData, callback) {
    console.log('=== 상품 선택 복원 시작 ===');
    
    const depth2Radio = document.querySelector(`input[name="depth2"][value="${formData.depth2}"]`);
    
    if (depth2Radio) {
        // depth2가 이미 렌더링되어 있는 경우
        depth2Radio.checked = true;
        depth2Radio.click(); // click 이벤트로 트리거
        console.log('✅ depth2 설정:', formData.depth2);
        
        // depth3 복원
        if (formData.depth3) {
            waitForDepth3ThenRestore(formData, callback);
        } else {
            if (callback) callback();
        }
    } else {
        // depth2가 아직 렌더링되지 않은 경우
        window.addEventListener('ehd.depth2.rendered', function depth2Handler() {
            const depth2Radio = document.querySelector(`input[name="depth2"][value="${formData.depth2}"]`);
            
            if (depth2Radio) {
                depth2Radio.checked = true;
                depth2Radio.click();
                console.log('✅ depth2 설정 (지연):', formData.depth2);
                
                if (formData.depth3) {
                    waitForDepth3ThenRestore(formData, callback);
                } else {
                    if (callback) callback();
                }
            }
            
            window.removeEventListener('ehd.depth2.rendered', depth2Handler);
        }, { once: true });
    }
}

// ========== depth3 대기 및 복원 ==========
function waitForDepth3ThenRestore(formData, callback) {
    const checkDepth3 = () => {
        const depth3Radio = document.querySelector(`input[name="depth3"][value="${formData.depth3}"]`);
        
        if (depth3Radio) {
            depth3Radio.checked = true;
            depth3Radio.click();
            console.log('✅ depth3 설정:', formData.depth3);
            if (callback) callback();
            return true;
        }
        return false;
    };
    
    if (checkDepth3()) return;
    
    window.addEventListener('ehd.depth3.rendered', function depth3Handler() {
        setTimeout(() => {
            if (checkDepth3()) {
                window.removeEventListener('ehd.depth3.rendered', depth3Handler);
            }
        }, 100);
    }, { once: true });
}

// ========== 여행 정보 복원 ==========
function restoreTravelInfo(formData) {
    console.log('=== 여행 정보 복원 시작 ===');
    
    if (formData.departure) {
        const departureInput = document.getElementById('A-departure');
        departureInput.value = formData.departure;
        
        // 함수 직접 호출
        onChangeEventListnerForDepartureText({ 
            target: departureInput,
            currentTarget: departureInput
        });
        
        console.log('✅ 출국일 설정:', formData.departure);
        
        // 시간 설정
        if (formData.departureTime) {
            waitForTimeOptions('A-departure-time', formData.departureTime, '출국');
        }
    }
    
    if (formData.arrival) {
        setTimeout(() => {
            const arrivalInput = document.getElementById('A-arrival');
            arrivalInput.value = formData.arrival;
            console.log('✅ 귀국일 설정:', formData.arrival);
            
            if (formData.arrivalTime) {
                setTimeout(() => {
                    const arrivalTimeSelect = document.getElementById('A-arrival-time');
                    arrivalTimeSelect.value = formData.arrivalTime;
                    console.log('✅ 귀국 시간 설정:', formData.arrivalTime);
                }, 150);
            }
        }, 700);
    }
}

// ========== 시간 옵션 대기 및 설정 ==========
function waitForTimeOptions(elementId, timeValue, label) {
    let attempts = 0;
    const maxAttempts = 15;
    
    const checkAndSet = () => {
        attempts++;
        const select = document.getElementById(elementId);
        const options = select.options;
        
        console.log(`${label} 시간 옵션 확인 (${attempts}/${maxAttempts}):`, options.length);
        
        if (options.length > 1) {
            select.value = timeValue;
            const actualValue = select.value;
            
            if (actualValue === timeValue) {
                console.log(`✅ ${label} 시간 설정: ${timeValue}`);
            } else {
                console.warn(`⚠️ ${label} 시간 불일치! 요청: ${timeValue}, 실제: ${actualValue}`);
            }
            return true;
        } else if (attempts < maxAttempts) {
            setTimeout(checkAndSet, 150);
            return false;
        } else {
            console.error(`❌ ${label} 시간 옵션 생성 실패`);
            return false;
        }
    }; 
    
    setTimeout(checkAndSet, 300);
}

// ========== 동반인 정보 복원 ==========
function restoreCompanions(formData) {
    console.log('=== 동반인 정보 복원 시작 ===');
    
    if (formData.companionCount && parseInt(formData.companionCount) > 0) {
        const countSelect = document.getElementById('B-count');
        
        // 동반인 수 설정
        countSelect.value = formData.companionCount;
        console.log('동반인 수 설정:', formData.companionCount);
        
        // generateCompanionForms 함수 직접 호출
        generateCompanionForms(formData.companionCount);
        console.log('동반인 입력 폼 생성 완료');
        
        // 동반인 입력 폼이 생성될 때까지 대기 후 값 입력
        setTimeout(() => {
            if (Array.isArray(formData.companions)) {
                formData.companions.forEach((companion, i) => {
                    const birthInput = document.getElementById(`B-birth-${i}`);
                    const genderRadio = document.querySelector(`input[name="B-gender-${i}"][value="${companion.gender}"]`);
                    
                    if (companion.birth && birthInput) {
                        birthInput.value = companion.birth;
                        console.log(`✅ 동반인 ${i+1} 생년월일:`, companion.birth);
                    }
                    
                    if (companion.gender && genderRadio) {
                        genderRadio.checked = true;
                        console.log(`✅ 동반인 ${i+1} 성별:`, companion.gender);
                    }
                });
                
                console.log('=== 동반인 정보 복원 완료 ===');
            }
            if (formData.bAgree === true) {
                const bAgreeCheckbox = document.getElementById('B-agree');
                if (bAgreeCheckbox) {
                    bAgreeCheckbox.checked = true;
                    console.log('✅ 동반인 동의 체크박스 복원');
                }
            }
        }, 500);
    } else {
        console.log('동반인 없음');
    }
}