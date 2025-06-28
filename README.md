# 🕒 Overtime Tracker

A web-based system to help organizations track, analyze, and manage employee overtime hours.  
It provides a digital filing workflow for employees and supervisors, as well as analytics on overtime reasons and trends.

---

## 📌 Overview

This system allows:

- Employees to **file overtime requests** with reasons and actual hours
- Supervisor to **approve**, **disapprove** (with remarks), or **mark as filed**
- Management to generate **weekly/monthly reports**
- Potential AI integration to analyze common reasons for overtime

---

## 🧩 Entity Relationship Diagram (ERD)

![ERD Diagram](overtime-tracker-erd.drawio)

> _The ERD outlines all major tables including employees, shifts, schedules, overtime requests, departments, and weekly hour rules._

---

## ⚙️ Tech Stack

- **Backend:** Laravel 11 (API-based)
- **Frontend:** Vue 3 + Inertia.js + TypeScript
- **Database:** MySQL
- **Auth:** Sanctum or token-based (planned)
- **Styling:** TailwindCSS