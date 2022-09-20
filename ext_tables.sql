#
# Table structure for table 'tx_jobapplications_domain_model_posting'
#
CREATE TABLE tx_jobapplications_domain_model_posting
(

	title               varchar(255)     DEFAULT ''  NOT NULL,
	categories          int(11) unsigned DEFAULT '0' NOT NULL,
	date_posted         date             DEFAULT NULL,
	career_level        varchar(255)     DEFAULT ''  NOT NULL,
	division            varchar(255)     DEFAULT ''  NOT NULL,
	employment_type     varchar(255)     DEFAULT ''  NOT NULL,
	terms_of_employment varchar(255)     DEFAULT ''  NOT NULL,
	company_description text,
	job_description     text,
	role_description    text,
	skill_requirements  text,
	benefits            text,
	base_salary         varchar(255)     DEFAULT ''  NOT NULL,
	valid_through       date             DEFAULT NULL,
	required_documents  text,
	company_information text,
	detail_view_image   int(11) unsigned             NOT NULL default '0',
	list_view_image     int(11) unsigned             NOT NULL default '0',
	locations            int(11) unsigned DEFAULT '0' NOT NULL,
	contact             int(11) unsigned DEFAULT '0',
	slug                varchar(255)     DEFAULT ''  NOT NULL
);

#
# Table structure for table 'tx_jobapplications_domain_model_contact'
#
CREATE TABLE tx_jobapplications_domain_model_contact
(

	first_name varchar(255) DEFAULT '' NOT NULL,
	last_name  varchar(255) DEFAULT '' NOT NULL,
	email      varchar(255) DEFAULT '' NOT NULL,
	phone      varchar(255) DEFAULT '' NOT NULL,
	division   varchar(255) DEFAULT '' NOT NULL,
	photo      int(11) unsigned        NOT NULL default '0',
	be_user    int(11) unsigned        NOT NULL DEFAULT '0'

);

#
# Table structure for table 'tx_jobapplications_domain_model_location'
#
CREATE TABLE tx_jobapplications_domain_model_location
(

	name                      varchar(255)     DEFAULT ''  NOT NULL,
	address_street_and_number varchar(255)     DEFAULT ''  NOT NULL,
	address_addition          varchar(255)     DEFAULT ''  NOT NULL,
	address_post_code         varchar(255)     DEFAULT ''  NOT NULL,
	address_city              varchar(255)     DEFAULT ''  NOT NULL,
	address_country           varchar(255)     DEFAULT ''  NOT NULL,
	latitude                  varchar(255)     DEFAULT ''  NOT NULL,
	londitude                 varchar(255)     DEFAULT ''  NOT NULL,
	categories                int(11) unsigned DEFAULT '0' NOT NULL,
	address_region			  varchar(255) 	   DEFAULT ''  NOT NULL

);

CREATE TABLE tx_jobapplications_postings_locations_mm (
                                                       uid_local int(11) unsigned DEFAULT '0' NOT NULL,
                                                       uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
                                                       sorting int(11) unsigned DEFAULT '0' NOT NULL,
                                                       sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

                                                       KEY uid_local (uid_local),
                                                       KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_jobapplications_domain_model_application'
#
CREATE TABLE tx_jobapplications_domain_model_application
(

	salutation                varchar(10)          DEFAULT ''  NOT NULL,
	first_name                varchar(255)         DEFAULT ''  NOT NULL,
	last_name                 varchar(255)         DEFAULT ''  NOT NULL,
	email                     varchar(255)         DEFAULT ''  NOT NULL,
	phone                     varchar(255)         DEFAULT ''  NOT NULL,
	address_street_and_number varchar(255)         DEFAULT ''  NOT NULL,
	address_addition          varchar(255)         DEFAULT ''  NOT NULL,
	address_post_code         int(11)              DEFAULT '0' NOT NULL,
	address_city              varchar(255)         DEFAULT ''  NOT NULL,
	address_country           varchar(255)         DEFAULT ''  NOT NULL,
	salary_expectation        varchar(255)         DEFAULT ''  NOT NULL,
	earliest_date_of_joining  date                 DEFAULT NULL,
	message                   text                 DEFAULT ''  NOT NULL,
	files                     int(11) unsigned     DEFAULT '0' NOT NULL,
	cv                        int(11) unsigned     DEFAULT '0' NOT NULL,
	cover_letter              int(11) unsigned     DEFAULT '0' NOT NULL,
	testimonials              int(11) unsigned     DEFAULT '0' NOT NULL,
	other_files               int(11) unsigned     DEFAULT '0' NOT NULL,
	privacy_agreement         smallint(5) unsigned DEFAULT '0' NOT NULL,
	posting                   int(11) unsigned     DEFAULT '0' NOT NULL,
	archived                  smallint(5) unsigned DEFAULT '0' NOT NULL,
	anonymized                smallint(5) unsigned DEFAULT '0' NOT NULL,
	status                    int(11) unsigned     DEFAULT '1' NOT NULL
);

#
# Table structure for table 'tx_jobapplications_domain_model_status'
#
CREATE TABLE tx_jobapplications_domain_model_status
(
	name          varchar(255)         DEFAULT ''  NOT NULL,
	is_end_status smallint(5) unsigned DEFAULT '0' NOT NULL,
	is_new_status smallint(5) unsigned DEFAULT '0' NOT NULL,
	followers     int(11)              DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_jobapplications_domain_model_status_mm'
#
CREATE TABLE tx_jobapplications_domain_model_status_mm
(
	uid_local       int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign     int(11) unsigned DEFAULT '0' NOT NULL,
	sorting         int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);