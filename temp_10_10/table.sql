
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
  insurance_id int(10) NOT NULL PRIMARY KEY,
  name varchar(30) NOT NULL,
  default_fee int(20) NOT NULL,
  disease_id int(10) NOT NULL,
  money int(20) NOT NULL,
  content varchar(200) NOT NULL,
  foreign key (disease_id) references disease
);

create table contract_list (
  contract_id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  google_id decimal(21,0) NOT NULL,
  insurance_id int(10) NOT NULL,
  real_fee int(20) NOT NULL,
  lastdiscounted date NOT NULL,
  foreign key (google_id) references google_users,
  foreign key (insurance_id) references insurance_data
);

create table disease(
	disease_id int(10) NOT NULL PRIMARY KEY,
	disease_name varchar(30) NOT NULL
);

create table medical_record (
	record_id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	google_id decimal(21,0) NOT NULL,
	disease_id int(10) NOT NULL,
	medicine_list varchar(40) NOT NULL,
	comment varchar(100) NOT NULL,
	
	foreign key (google_id) references google_users,
	foreign key (disease_id) references disease
);

create table twitter_words (
	word varchar(20) NOT NULL,
	type tinyint(1) NOT NULL
);


insert into twitter_words values('과일', 1);
insert into twitter_words values('사과', 1);
insert into twitter_words values('배', 1);
insert into twitter_words values('수박', 1);
insert into twitter_words values('포도', 1);
insert into twitter_words values('야채', 1);
insert into twitter_words values('샐러드', 1);
insert into twitter_words values('비타민', 1);

insert into twitter_words values('운동', 1);
insert into twitter_words values('헬스', 1);
insert into twitter_words values('줄넘기', 1);
insert into twitter_words values('달리기', 1);
insert into twitter_words values('조깅', 1);
insert into twitter_words values('뛰기', 1);
insert into twitter_words values('걷기', 1);

insert into twitter_words values('갓길용', 1);

insert into twitter_words values('피자', 0);
insert into twitter_words values('치킨', 0);
insert into twitter_words  values('햄버거', 0);
insert into twitter_words  values('햄버그', 0);
insert into twitter_words  values('라면', 0);
insert into twitter_words  values('사탕', 0);
insert into twitter_words  values('초콜릿', 0);
insert into twitter_words  values('탄산음료', 0);
insert into twitter_words  values('콜라', 0);
insert into twitter_words  values('사이다', 0);
insert into twitter_words  values('과자', 0);
insert into twitter_words  values('아이스크림', 0);
insert into twitter_words  values('감자칩', 0);
insert into twitter_words  values('감자튀김', 0);
insert into twitter_words  values('감튀', 0);
insert into twitter_words  values('베이컨', 0);
insert into twitter_words  values('커피', 0);
insert into twitter_words  values('인스턴트', 0);
insert into twitter_words  values('튀김', 0);
insert into twitter_words values('패스트푸드', 0);
insert into twitter_words values('맥도날드', 0);
insert into twitter_words values('롯데리아', 0);

insert into twitter_words  values('담배', 0);
insert into twitter_words  values('술', 0);
insert into twitter_words  values('소주', 0);
insert into twitter_words  values('맥주', 0);
insert into twitter_words  values('와인', 0);
insert into twitter_words  values('양주', 0);
insert into twitter_words  values('막걸리', 0);

insert into twitter_words  values('유영호', 0);


insert into admin_doctor values('admin', '1234', 'smrmt', 0);
insert into admin_doctor values('doctor', '1234', 'dmltk', 1);

insert into insurance_data values(1, '보험 1', 30000, 1, 10000000, '혈압이 보통인 당신에게, 심장병 치료시 1000만원 지원');
insert into insurance_data values(2, '보험 2', 40000, 1, 20000000, '고혈압 위험군인 당신에게, 심장병 치료시 2000만원 지원');
insert into insurance_data values(3, '보험 3', 60000, 1, 40000000, '고혈압인 당신에게, 심장병 치료시 4000만원 지원');

insert into disease values(1, '심장병');
insert into disease values(2, '당뇨병');
