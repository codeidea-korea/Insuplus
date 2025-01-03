## Insuplus System

#### Servers

1. Live Server

   > [EHD Redmine](http://aws1.ehd.kr:7999/) 프로젝트 인슈플러스 참고

   - domain : insuplus.co.kr
   - ip: 14.63.172.229
   - id: insplus / pw: !insplus#
   - DBip: 14.63.172.229:3306
   - Database id: root
   - Database pw: !insplus#
   - DBname: insplus
   - Source Repository : https://github.com/neo4elf-ehd/biz_insplus.git _branch : master_

2. Dev Server
   - domain : biz-dev.ehd.kr
   - IP : 14.63.187.24
   - SSH2 Port : 22
   - System Account : root / !assistancetest1
   - 도커 접속 : PHP_INS (alias 등록함)
   - DBip: 14.63.187.24:3306
   - Database id: root
   - Database pw: !insplus#
   - DBname: insplus
   - Source Repository : https://github.com/neo4elf-ehd/biz_insplus.git _branch : development, renewal_

[메인 브랜치 규칙]
1. master: 배포되는 브랜치 
2. development: 개발 브랜치
*development 브랜치에서 아래 하위 브랜치 규칙에 따라 개발/테스트 후 배포 시 master 브랜치에 merge
*master브랜치에서 에서 체크아웃한 코드는 반드시 실행 가능해야함

[하위 브랜치 규칙]
1. 기능개발 
- features/기능코드
- 예:features/front_main_menu

2. 버그 수정
- hotfix/버그코드
- 예:hotfix/log_error

[태그]
1. master 브렌치에 머지 후 RELEASE_버전명으로 태깅
-예:RELEASE_v1.0.0

[커밋 Prefix 규칙]
1. feat: 기능 추가
- feat:로그인버튼 추가
2. fix: 버그 수정
- fix:0으로 나누기 버그 수정
3. build: 기능, 버그 수정 없이 단순 버전 업
- build:1.0.1

[개발환경 구성방법]
1) WSL2 활성화
2) Docker for windows 설치

[docker compsoe 실행]
- docker-compose.yml 설정 참고
1) cmd실행하여 프로젝트 폴더로 이동 후 아래 명령어 실행
 - docker-compose up -d

[참고사항]
1. _config/config.php에서 설정 변경하여 DB 접속주소, PG 결제시 실결제 or 테스트 결제로 변경 가능함 (54L부터 주석 참고)

[배포방법]
1. 프로젝트 경로로 이동
2. git 명령어로 소스 현행화
   - git pull origin #branch name
   - git pull origin main (운영기준준) 