drop database opis;
create database opis;
use opis;

set names utf8;
alter database opis default character set = utf8;

create table google_users
(
  google_id decimal(21,0) NOT NULL PRIMARY KEY,
  google_name varchar(60) NOT NULL,
  google_email varchar(60) NOT NULL,
  google_link varchar(60) NOT NULL,
  google_picture_link varchar(60) NOT NULL,
  age int(8) NOT NULL,
  sex tinyint(1) NOT NULL,
  job varchar(40) NOT NULL
);

create table admin_doctor (
  id varchar(40) NOT NULL PRIMARY KEY,
  password varchar(40) NOT NULL,
  name varchar(20) NOT NULL,
  type tinyint(1) NOT NULL
);

create table insurance_data (
  insurance_id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name varchar(30) NOT NULL,
  default_fee int(20) NOT NULL,
      
  money int(20) NOT NULL,
  content varchar(200) NOT NULL
);

create table disease_data (
	disease_id int(10) NOT NULL PRIMARY KEY,
	disease_name varchar(30) NOT NULL
);

create table insurance_disease( 
  insurance_id int(10) NOT NULL,
  disease_id int(10) NOT NULL,
   
  foreign key (insurance_id) references insurance_data,
  foreign key (disease_id) references disease_data
);

create table contract_list (
  contract_id int(10) NOT NULL primary key AUTO_INCREMENT,
  google_id decimal(21,0) NOT NULL,
  insurance_id int(10) NOT NULL,
  real_fee int(20) NOT NULL,
  lastdiscounted date NOT NULL,
  
  unique key (google_id, insurance_id),
  foreign key (google_id) references google_users,
  foreign key (insurance_id) references insurance_data
);

create table receive_list( 
	receive_id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	record_id int(10) NOT NULL,
	google_id decimal(21,0) NOT NULL,
	money int(20) NOT NULL,
	receive_date date NOT NULL,
	insurance_id int(10) NOT NULL,
	
	foreign key (google_id) references google_users,
	foreign key (record_id) references medical_record
);

create table medical_record (
	record_id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	google_id decimal(21,0) NOT NULL,
	disease_id int(10) NOT NULL,
	medicine_list varchar(40) NOT NULL,
	record_date date NOT NULL,
	comment varchar(100) NOT NULL,
	
	foreign key (google_id) references google_users,
	foreign key (disease_id) references disease_data
);

create table twitter_words (
	word varchar(30) NOT NULL,
	type tinyint(1) NOT NULL	
);


create table twitter_eaten_words (
	word varchar(30) NOT NULL
);


insert into twitter_words values('과일', 1);
insert into twitter_words values('사과', 1);
insert into twitter_words values('바나나', 1);
insert into twitter_words values('딸기', 1);
insert into twitter_words values('배', 1);
insert into twitter_words values('수박', 1);
insert into twitter_words values('복숭아', 1);
insert into twitter_words values('블루베리', 1);
insert into twitter_words values('한라봉', 1);
insert into twitter_words values('참외', 1);
insert into twitter_words values('감', 1);
insert into twitter_words values('토마토', 1);
insert into twitter_words values('매실', 1);
insert into twitter_words values('망고', 1);
insert into twitter_words values('포도', 1);
insert into twitter_words values('호두', 1);
insert into twitter_words values('야채', 1);
insert into twitter_words values('샐러드', 1);
insert into twitter_words values('과일주스', 1);
insert into twitter_words values('오메가3', 1);
insert into twitter_words values('비타민', 1);
insert into twitter_words values('유산균', 1);

insert into twitter_words values('갓길용', 1);
insert into twitter_words values('피자', 0);
insert into twitter_words values('치킨', 0);
insert into twitter_words values('치느님', 0);
insert into twitter_words  values('햄버거', 0);
insert into twitter_words  values('햄버그', 0);
insert into twitter_words  values('라면', 	0);
insert into twitter_words  values('안성탕면', 0);
insert into twitter_words  values('쫄면', 0);
insert into twitter_words  values('짜장면', 0);
insert into twitter_words  values('사탕', 0);
insert into twitter_words  values('초콜릿', 0);
insert into twitter_words  values('탄산음료', 0);
insert into twitter_words  values('콜라', 0);
insert into twitter_words  values('사이다', 0);
insert into twitter_words  values('환타', 0);


insert into twitter_eaten_words values('먹고');
insert into twitter_eaten_words values('먹음');
insert into twitter_eaten_words values('머금');
insert into twitter_eaten_words values('흡입');
insert into twitter_eaten_words values('폭풍 흡입');
insert into twitter_eaten_words values('포풍 흡입');
insert into twitter_eaten_words values('먹었긔');
insert into twitter_eaten_words values('먹었다');
insert into twitter_eaten_words values('먹었소');
insert into twitter_eaten_words values('머거쪙');
insert into twitter_eaten_words values('마심');
insert into twitter_eaten_words values('마셨다');
insert into twitter_eaten_words values('막 머금');
insert into twitter_eaten_words values('막머금');
insert into twitter_eaten_words values('먹었');
insert into twitter_eaten_words values('먹엇');
insert into twitter_eaten_words values('머거씀');
insert into twitter_eaten_words values('먹어야지');
insert into twitter_eaten_words values('맛있음');
insert into twitter_eaten_words values('마이쪙');
insert into twitter_eaten_words values('먹는다');
insert into twitter_eaten_words values('뫄이쪙');
insert into twitter_eaten_words values('냠냠');
insert into twitter_eaten_words values('핵존맛');
insert into twitter_eaten_words values('존맛');
insert into twitter_eaten_words values('시켰');
insert into twitter_eaten_words values('시킴');
insert into twitter_eaten_words values('머거쪄');


insert into twitter_words  values('과자', 0);
insert into twitter_words  values('아이스크림', 0);
insert into twitter_words  values('감자칩', 0);
insert into twitter_words  values('감자튀김', 0);
insert into twitter_words  values('감튀', 0);
insert into twitter_words  values('베이컨', 0);
insert into twitter_words  values('커피', 0);
insert into twitter_words  values('믹스커피', 0);
insert into twitter_words  values('라떼', 0);
insert into twitter_words  values('인스턴트', 0);
insert into twitter_words  values('튀김', 0);

insert into twitter_words values('패스트푸드', 0);
insert into twitter_words values('맥도날드', 0);
insert into twitter_words values('맥날', 0);
insert into twitter_words values('롯데리아', 0);
insert into twitter_words values('버거킹', 0);
insert into twitter_words values('롯데리아', 0);
insert into twitter_words  values('담배', 0);
insert into twitter_words  values('술', 0);

insert into twitter_words  values('양주', 0);
insert into twitter_words  values('발렌타인', 0);

insert into twitter_words  values('소주', 0);
insert into twitter_words  values('순하리', 0);
insert into twitter_words  values('자몽소주', 0);
insert into twitter_words  values('유자소주', 0);
insert into twitter_words  values('좋은데이', 0);
insert into twitter_words  values('참이슬', 0);
insert into twitter_words  values('자몽에이슬', 0);

insert into twitter_words  values('하이트', 0);
insert into twitter_words  values('카스', 0);
insert into twitter_words  values('기네스', 0);
insert into twitter_words  values('스타우트', 0);
insert into twitter_words  values('하이네켄', 0);
insert into twitter_words  values('생맥', 0);

insert into twitter_words  values('맥주', 0);
insert into twitter_words  values('와인', 0);
insert into twitter_words  values('소비뇽', 0);
insert into twitter_words  values('와인', 0);
insert into twitter_words  values('스파클링', 0);
insert into twitter_words  values('와인', 0);
insert into twitter_words  values('양주', 0);
insert into twitter_words  values('막걸리', 0);
insert into twitter_words  values('담배', 0);

insert into twitter_words  values('유영호', 0);


insert into admin_doctor values('admin', '1234', 'smrmt', 0);
insert into admin_doctor values('doctor', '1234', 'dmltk', 1);

insert into insurance_data values(1, '보험 1', 30000, 10000000, '보험 1 설명');
insert into insurance_data values(2, '보험 2', 40000, 20000000, '보험 2 설명');
insert into insurance_data values(3, '보험 3', 60000, 40000000, '보험 3 설명');

insert into disease_data values(1, '골관절염');
insert into disease_data values(2, '당뇨병');
insert into disease_data values(3, '아토피성 피부염');
insert into disease_data values(4, '우울증');
insert into disease_data values(5, '고혈압');

insert into insurance_disease values(1,1);
insert into insurance_disease values(1,2);
insert into insurance_disease values(1,3);

insert into insurance_disease values(2,3);
insert into insurance_disease values(2,4);
insert into insurance_disease values(2,5);

insert into insurance_disease values(3,4);
insert into insurance_disease values(3,5);

