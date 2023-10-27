SET FOREIGN_KEY_CHECKS = 0;
-- MySQL dump 10.19  Distrib 10.3.31-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: weberpdemo
-- ------------------------------------------------------


--
-- Dumping data for table `scripts`
--

INSERT INTO `scripts` VALUES ('egg_DayOldBroodingStage.php', 15, 'Manage the brooding stage of day-old chicks');
INSERT INTO `scripts` VALUES ('egg_ReceiptLayingbirds.php', 15, 'Manage the receipt of laying birds');
INSERT INTO `scripts` VALUES ('egg_TransferFromBroodingToRearing.php', 15, 'Manage the transfer from brooding to rearing');
INSERT INTO `scripts` VALUES ('egg_TransferFromRearingToProduction.php', 15, 'Manage the transfer from rearing to production');
INSERT INTO `scripts` VALUES ('egg_BroodingManager.php', 15, 'Manage brooding');
INSERT INTO `scripts` VALUES ('egg_RearingManager.php', 15, 'Manage rearing');
INSERT INTO `scripts` VALUES ('egg_LayingManager.php', 15, 'Manage laying');
INSERT INTO `scripts` VALUES ('egg_LayingManagerPOL.php', 15, 'Manage laying (Point of Lay)');
INSERT INTO `scripts` VALUES ('egg_DailyProduction.php', 15, 'Manage daily egg production');
INSERT INTO `scripts` VALUES ('egg_TransferToHatching.php', 15, 'Transfer eggs to hatching');
INSERT INTO `scripts` VALUES ('egg_HatchingManagement.php', 15, 'Manage hatching');

INSERT INTO `scripts` VALUES ('egg_RearingHousesInquiry.php', 15, 'View all rearing houses inquiry');
INSERT INTO `scripts` VALUES ('egg_RearingWeeklyExpenses.php', 15, 'View rearing weekly expenses');
INSERT INTO `scripts` VALUES ('egg_ViewSpecificExpensesCategoriesWeekly.php', 15, 'View specific expenses categories weekly');
INSERT INTO `scripts` VALUES ('egg_WeeklyMortalityRateByPen.php', 15, 'View weekly mortality rate by pen');
INSERT INTO `scripts` VALUES ('egg_WeeklyCostOfRearingPerBird.php', 15, 'View weekly cost of rearing per bird');
INSERT INTO `scripts` VALUES ('egg_VaccinationScheduleReport.php', 15, 'View vaccination schedule report');
INSERT INTO `scripts` VALUES ('egg_VaccinationActivities.php', 15, 'View vaccination activities');
INSERT INTO `scripts` VALUES ('egg_FeedConversionRation.php', 15, 'View feed conversion ratio by pen');
INSERT INTO `scripts` VALUES ('egg_ViewAllocatedResources.php', 15, 'View allocated resources');
INSERT INTO `scripts` VALUES ('egg_HatchingoperationsInquiry.php', 15, 'View all hatching operations inquiry');
INSERT INTO `scripts` VALUES ('egg_AllHachedChicksInquiry.php', 15, 'View all hatched chicks inquiry');

INSERT INTO `scripts` VALUES ('egg_RearingHouses.php', 15, 'Manage rearing houses');
INSERT INTO `scripts` VALUES ('egg_RearingExpenses.php', 15, 'Manage rearing expenses');
INSERT INTO `scripts` VALUES ('egg_VaccinationScheduleWithSMS.php', 15, 'Setup vaccination schedule for laying with SMS');
INSERT INTO `scripts` VALUES ('egg_BirdBreeds.php', 15, 'Manage bird breeds');
INSERT INTO `scripts` VALUES ('egg_ManagementPractices.php', 15, 'Setup occasional management practices');
INSERT INTO `scripts` VALUES ('egg_GradingParameters.php', 15, 'Setup grading parameters');
INSERT INTO `scripts` VALUES ('egg_AllocateApprovedInventoryItems.php', 15, 'Allocate approved inventory items');


CREATE TABLE rearing_houses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag_id tinyint,
    rearing_stage ENUM('BROODING PHASE', 'REARING PHASE', 'PRODUCTION PHASE'),
    branch_code VARCHAR(255),
    location_code VARCHAR(255),
    location_name VARCHAR(255),
    holding_capacity INT,
    contact_for_deliveries VARCHAR(255),
    phone VARCHAR(20),
    created_by VARCHAR(255),
    date DATE,
    FOREIGN KEY (tag_id) REFERENCES tags(tagref)
);

CREATE TABLE poultry_stage_expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rearing_stage ENUM('BROODING PHASE', 'REARING PHASE', 'PRODUCTION PHASE', 'ALL PHASE'),
    category_id INT,
    created_by VARCHAR(255),
    date DATE,
    FOREIGN KEY (category_id) REFERENCES stockcategory(categoryid)
);

CREATE TABLE vaccination_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    age_in_days INT,
    age_in_weeks INT,
    is_in_days BOOLEAN,
    from_day ENUM('DAY 1', 'DAY 2', 'DAY 3', 'DAY 4', 'DAY 5', 'DAY 6', 'DAY 7', 'DAY 8', 'DAY 9', 'DAY 10', 'DAY 11', 'DAY 12', 'DAY 13', 'DAY 14', 'DAY 15', 'DAY 16', 'DAY 17', 'DAY 18', 'DAY 19', 'DAY 20', 'DAY 21', 'DAY 22', 'DAY 23', 'DAY 24', 'DAY 25', 'DAY 26', 'DAY 27', 'DAY 28', 'DAY 29', 'DAY 30'),
    to_day ENUM('DAY 1', 'DAY 2', 'DAY 3', 'DAY 4', 'DAY 5', 'DAY 6', 'DAY 7', 'DAY 8', 'DAY 9', 'DAY 10', 'DAY 11', 'DAY 12', 'DAY 13', 'DAY 14', 'DAY 15', 'DAY 16', 'DAY 17', 'DAY 18', 'DAY 19', 'DAY 20', 'DAY 21', 'DAY 22', 'DAY 23', 'DAY 24', 'DAY 25', 'DAY 26', 'DAY 27', 'DAY 28', 'DAY 29', 'DAY 30'),
    is_in_weeks BOOLEAN,
    week_from ENUM('WEEK 1', 'WEEK 2', 'WEEK 3', 'WEEK 4', 'WEEK 5', 'WEEK 6', 'WEEK 7'),
    week_to ENUM('WEEK 1', 'WEEK 2', 'WEEK 3', 'WEEK 4', 'WEEK 5', 'WEEK 6', 'WEEK 7'),
    disease_name VARCHAR(255),
    vaccine_name VARCHAR(255),
    route_delivery_method VARCHAR(255),
    alert_user_id INT,
    created_by VARCHAR(255),
    date DATE,
    FOREIGN KEY (alert_user_id) REFERENCES www_users(userid)
);
