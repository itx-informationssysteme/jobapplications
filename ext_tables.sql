#
# Table structure for table 'tx_jobs_domain_model_posting'
#
CREATE TABLE tx_jobs_domain_model_posting (

	title varchar(255) DEFAULT '' NOT NULL,
	date_posted date DEFAULT NULL,
	career_level varchar(255) DEFAULT '' NOT NULL,
	division varchar(255) DEFAULT '' NOT NULL,
	employment_type varchar(255) DEFAULT '' NOT NULL,
	terms_of_employment varchar(255) DEFAULT '' NOT NULL,
	company_description text,
	job_description text,
	role_description text,
	skill_requirements text,
	benefits text,
	base_salary varchar(255) DEFAULT '' NOT NULL,
	valid_through date DEFAULT NULL,
	required_documents text,
	company_information text,
	location int(11) unsigned DEFAULT '0',
	contact int(11) unsigned DEFAULT '0',

);

#
# Table structure for table 'tx_jobs_domain_model_contact'
#
CREATE TABLE tx_jobs_domain_model_contact (

	name varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	phone varchar(255) DEFAULT '' NOT NULL,
	division varchar(255) DEFAULT '' NOT NULL,

);

#
# Table structure for table 'tx_jobs_domain_model_location'
#
CREATE TABLE tx_jobs_domain_model_location (

	name varchar(255) DEFAULT '' NOT NULL,
	address text,

);

#
# Table structure for table 'tx_jobs_domain_model_application'
#
CREATE TABLE tx_jobs_domain_model_application (

	salutation int(11) DEFAULT '0' NOT NULL,
	first_name varchar(255) DEFAULT '' NOT NULL,
	last_name varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	phone varchar(255) DEFAULT '' NOT NULL,
	address text,
	salary_expectation varchar(255) DEFAULT '' NOT NULL,
	earliest_date_of_joining date DEFAULT NULL,
	cv int(11) unsigned NOT NULL default '0',
	cover_letter int(11) unsigned NOT NULL default '0',
	testimonials int(11) unsigned NOT NULL default '0',
	other_files int(11) unsigned NOT NULL default '0',
	privacy_agreement smallint(5) unsigned DEFAULT '0' NOT NULL,
	posting int(11) unsigned DEFAULT '0',

);

#
# Table structure for table 'tx_jobs_domain_model_posting'
#
CREATE TABLE tx_jobs_domain_model_posting (
	categories int(11) unsigned DEFAULT '0' NOT NULL,
);
