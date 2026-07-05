# 🩺 CompanioAI – AI-Powered Elderly Healthcare Monitoring Platform

CompanioAI is an AI-powered healthcare platform designed to improve the quality of life for elderly individuals by providing intelligent health monitoring, medication management, emergency detection, and caregiver support.

The platform combines Artificial Intelligence, real-time monitoring, healthcare analytics, and caregiver collaboration into a single web application.

---

# 🌟 Features

## 👴 Elderly Portal

* AI Health Assistant
* Voice-based interaction
* Health reporting
* Medication reminders
* Emergency assistance
* Medical history access
* Daily health monitoring

---

## 👨‍⚕️ Caregiver Portal

* Live monitoring dashboard
* Elderly health overview
* Medication tracking
* Appointment management
* Emergency alerts
* Medical history
* Daily AI reports

---

## 🤖 AI Health Monitoring System

The application continuously monitors elderly users using AI.

### Continuous Monitoring

* Medication compliance
* Missed medicines detection
* Inactivity monitoring
* AI conversation analysis
* Emergency keyword detection
* Health report analysis
* Daily health score generation

---

## 🚨 Emergency Detection

Automatically detects emergency situations such as:

* Help
* I fell
* Chest pain
* Can't breathe
* Severe pain
* Emergency
* Call caregiver
* Dizziness
* Unconscious

When detected, the system automatically:

* Creates emergency alerts
* Notifies caregivers
* Records the incident
* Calculates severity
* Stores timestamps

---

## 🧠 AI Risk Assessment

The AI evaluates multiple health indicators including:

* Missed medications
* Blood pressure
* Heart rate
* Oxygen saturation
* Temperature
* AI conversations
* Manual health reports
* User inactivity

Risk Levels:

* ✅ Normal
* 🟢 Low Risk
* 🟡 Medium Risk
* 🟠 High Risk
* 🔴 Critical

Each assessment includes:

* Confidence score
* AI explanation
* Recommended caregiver action

---

## 💊 Medication Intelligence

* Medication reminders
* Missed medicine detection
* Late medicine detection
* Consecutive missed dose detection
* Medication compliance percentage
* Compliance trends

---

## 📊 Live Caregiver Dashboard

Real-time dashboard displaying:

* Online elderly users
* Last activity
* Current health status
* Medication status
* AI Risk Level
* Emergency alerts
* Daily health score

Dashboard automatically refreshes using AJAX.

---

## 📈 Health Timeline

Chronological timeline including:

* Medication records
* AI conversations
* Health reports
* Emergency alerts
* Caregiver actions

---

## 📋 Daily AI Health Reports

Automatically generated reports include:

* Overall health summary
* Medication compliance
* AI conversation summary
* Health risk analysis
* AI recommendations

---

## 🔔 Notifications

Supports:

* Dashboard notifications
* Email notifications
* Twilio SMS (optional)

---

# 🛠 Technology Stack

### Frontend

* HTML5
* CSS3
* Tailwind CSS
* JavaScript
* AJAX

### Backend

* PHP 8+
* Object-Oriented PHP

### Database

* MySQL

### AI

* Groq API
* Modular AI Services

### Server

* Apache (XAMPP)
* PHP
* MySQL

---

# 📂 Project Structure

```text
CompanioAI/
│
├── ai/
├── api/
├── assets/
├── auth/
├── caregiver/
├── elderly/
├── services/
├── cron/
├── config/
├── database/
├── index.php
└── README.md
```

---

# 🚀 Installation

1. Clone the repository

```bash
git clone https://github.com/Ezekielg123/ComapnioAI.git
```

2. Move the project into your XAMPP `htdocs` folder.

3. Start Apache and MySQL.

4. Import `database/schema.sql` into MySQL.

5. Configure your database credentials in:

```
config/database.php
```

6. Add your own Groq API key in:

```
config/ai.php
```

**Note:** The API key is intentionally excluded from this repository for security reasons.

7. Open:

```
http://localhost/CompanioAI
```

---

# 🔒 Security

* Prepared SQL statements
* Input validation
* XSS prevention
* CSRF protection
* Secure authentication
* Session management
* API key excluded from GitHub

---

# 📌 Future Enhancements

* Wearable device integration
* IoT health sensors
* AI predictive health analytics
* Video consultation
* Mobile application
* WhatsApp notifications
* Cloud deployment
* Multi-language support

---

# 👨‍💻 Developer

**Ezekiel**

GitHub: https://github.com/Ezekielg123

---

# 📄 License

This project is released under the MIT License.

---

# ⭐ Acknowledgements

CompanioAI was developed as an AI-powered healthcare platform for elderly assistance, combining modern AI technologies with healthcare monitoring to demonstrate practical, real-world intelligent caregiving solutions.

If you found this project useful, please consider giving it a ⭐ on GitHub.
