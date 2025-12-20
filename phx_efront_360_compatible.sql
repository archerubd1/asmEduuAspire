
-- =====================================================================
-- PHX 360° Learner Profiling Subsystem - eFront 3.15 Compatible Schema
-- Version: 1.0 | Environment: UwAmp (PHP 5.4) / GoDaddy (MySQL 8)
-- Author: Phoenix Innovates LXP Team
-- =====================================================================

-- =============================================================
-- 1. LEARNERS - Extension of eFront USERS table
-- =============================================================
CREATE TABLE IF NOT EXISTS learners (
  learner_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  nationality VARCHAR(100),
  timezone VARCHAR(50) DEFAULT 'Asia/Kolkata',
  gender ENUM('Male','Female','Other') DEFAULT NULL,
  dob DATE DEFAULT NULL,
  city VARCHAR(100),
  state VARCHAR(100),
  country VARCHAR(100),
  phone VARCHAR(20),
  profile_photo VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 2. EDUCATION & CERTIFICATIONS
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_education (
  edu_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  institution VARCHAR(255),
  qualification VARCHAR(255),
  start_year YEAR,
  end_year YEAR,
  is_current TINYINT(1) DEFAULT 0,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_certifications (
  cert_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  certification_name VARCHAR(255),
  issuing_org VARCHAR(255),
  issue_date DATE,
  expiry_date DATE,
  credential_id VARCHAR(100),
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 3. PSYCHOGRAPHICS & PREFERENCES
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_psychographics (
  psy_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  motivation VARCHAR(255),
  learning_style ENUM('Visual','Auditory','Reading/Writing','Kinesthetic') DEFAULT NULL,
  cognitive_pattern VARCHAR(255),
  engagement_type ENUM('Social','Independent') DEFAULT 'Independent',
  gamification_persona ENUM('Achiever','Explorer','Socializer','Killer') DEFAULT 'Achiever',
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_preferences (
  pref_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  content_language VARCHAR(50) DEFAULT 'English',
  preferred_format ENUM('Video','Text','Interactive','Mixed') DEFAULT 'Mixed',
  notifications_enabled TINYINT(1) DEFAULT 1,
  ai_personalization_optin TINYINT(1) DEFAULT 1,
  privacy_consent_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 4. BEHAVIORAL & ENGAGEMENT ANALYTICS
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_activity_log (
  log_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED DEFAULT NULL,
  event_type VARCHAR(100),
  time_spent INT DEFAULT 0,
  score DECIMAL(5,2) DEFAULT NULL,
  activity_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_engagement (
  engage_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  total_sessions INT DEFAULT 0,
  avg_duration INT DEFAULT 0,
  completion_rate DECIMAL(5,2) DEFAULT 0.00,
  accuracy_rate DECIMAL(5,2) DEFAULT 0.00,
  engagement_score DECIMAL(6,2) DEFAULT 0.00,
  last_active DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 5. CAREER ANALYTICS
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_goals (
  goal_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  goal_type ENUM('Career','Skill','LearningPath') DEFAULT 'LearningPath',
  description TEXT,
  target_date DATE DEFAULT NULL,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_career_pathways (
  path_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  current_role VARCHAR(255),
  desired_role VARCHAR(255),
  skill_gap TEXT,
  ai_recommendation TEXT,
  confidence_score DECIMAL(4,2) DEFAULT 0.85,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 6. GAMIFICATION & IMMERSIVE LEARNING
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_rewards (
  reward_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  badge_name VARCHAR(255),
  points INT DEFAULT 0,
  level INT DEFAULT 1,
  awarded_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_metaverse_logs (
  meta_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  session_id VARCHAR(100),
  environment_name VARCHAR(255),
  duration_minutes INT DEFAULT 0,
  achievements TEXT,
  session_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 7. AI FEATURE STORE
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_ai_features (
  feature_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  ai_vector TEXT,
  skill_similarity TEXT COMMENT 'Stores JSON-encoded skill similarity data',
  engagement_pattern TEXT COMMENT 'Stores JSON-encoded engagement patterns',
  prediction_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (learner_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS learner_recommendations (
  rec_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  recommendation_type ENUM('Course','Skill','Career','Mentor','Content') NOT NULL,
  reference_id VARCHAR(100),
  recommendation_reason TEXT,
  confidence_score DECIMAL(4,2) DEFAULT 0.85,
  action_status ENUM('Pending','Clicked','Ignored','Completed') DEFAULT 'Pending',
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 8. SOCIAL & COMMUNITY LEARNING
-- =============================================================
CREATE TABLE IF NOT EXISTS learning_groups (
  group_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  group_name VARCHAR(255),
  description TEXT,
  course_id INT UNSIGNED DEFAULT NULL,
  facilitator_id INT UNSIGNED DEFAULT NULL,
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_group_mapping (
  map_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  group_id INT UNSIGNED NOT NULL,
  role ENUM('Learner','Facilitator','Mentor') DEFAULT 'Learner',
  join_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Active','Inactive') DEFAULT 'Active',
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE,
  FOREIGN KEY (group_id) REFERENCES learning_groups(group_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_social_interactions (
  interaction_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_id INT UNSIGNED NOT NULL,
  receiver_id INT UNSIGNED NOT NULL,
  interaction_type ENUM('Message','Comment','Like','PeerReview','MentorshipRequest') NOT NULL,
  context VARCHAR(255),
  content TEXT,
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES learners(learner_id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 9. FEEDBACK & EMOTION ANALYTICS
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_feedback_responses (
  feedback_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED DEFAULT NULL,
  feedback_type ENUM('Course','Instructor','Platform','Feature') DEFAULT 'Course',
  rating INT DEFAULT NULL,
  comments TEXT,
  sentiment ENUM('Positive','Neutral','Negative') DEFAULT 'Neutral',
  submitted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_emotion_metrics (
  emotion_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  session_id VARCHAR(100),
  emotion_label ENUM('Focused','Bored','Confused','Engaged','Frustrated','Happy') DEFAULT 'Focused',
  confidence DECIMAL(4,2) DEFAULT 0.90,
  detected_by ENUM('CameraAI','VoiceAI','TextSentiment','SelfReport') DEFAULT 'SelfReport',
  recorded_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 10. SYSTEM UTILITIES & AUDIT
-- =============================================================
CREATE TABLE IF NOT EXISTS learner_notifications (
  notification_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  title VARCHAR(255),
  message TEXT,
  type ENUM('System','Course','Reward','Reminder','Mentorship') DEFAULT 'System',
  is_read TINYINT(1) DEFAULT 0,
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_system_telemetry (
  telemetry_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  device_info VARCHAR(255),
  browser_info VARCHAR(255),
  os_info VARCHAR(100),
  network_speed DECIMAL(6,2) DEFAULT NULL,
  latency_ms INT DEFAULT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learner_audit_log (
  audit_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  learner_id INT UNSIGNED NOT NULL,
  change_type ENUM('ProfileUpdate','AIInference','SystemAction') DEFAULT 'ProfileUpdate',
  change_details TEXT,
  changed_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (learner_id) REFERENCES learners(learner_id) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- 11. VIEW FOR UNIFIED PROFILE ACCESS
-- =============================================================
CREATE OR REPLACE VIEW vw_learner_360_profile AS
SELECT
  u.id AS user_id,
  u.login,
  u.email,
  l.learner_id,
  l.country,
  l.city,
  l.state,
  p.motivation,
  p.learning_style,
  e.engagement_score,
  a.skill_similarity,
  a.engagement_pattern
FROM users u
JOIN learners l ON u.id = l.user_id
LEFT JOIN learner_psychographics p ON l.learner_id = p.learner_id
LEFT JOIN learner_engagement e ON l.learner_id = e.learner_id
LEFT JOIN learner_ai_features a ON l.learner_id = a.learner_id;

-- =====================================================================
-- END OF FILE
-- =====================================================================
