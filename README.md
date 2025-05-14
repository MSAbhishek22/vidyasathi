# 📚 VidyaSathi – Empowering the Educational Community

![Made with PHP](https://img.shields.io/badge/Made%20with-PHP-blue)
![License MIT](https://img.shields.io/badge/License-MIT-green)
![Open Source Love](https://img.shields.io/badge/Open%20Source-%E2%9D%A4-red)
![Contributions Welcome](https://img.shields.io/badge/Contributions-Welcome-brightgreen)

---

**VidyaSathi** is a dynamic educational platform that brings students and educators together in one vibrant online community. Whether you're studying for exams, sharing knowledge, or seeking help, VidyaSathi has your back!

---

## 🚀 Live Demo

🌐 Coming soon!  
_(You can host the project on your local server via XAMPP for now.)_

---

## 📑 Table of Contents

- [Features](#-features)
- [AI Chatbot & Student Wellness Companion](#-ai-chatbot--student-wellness-companion)
- [Technologies Used](#-technologies-used)
- [Installation Guide](#-installation-guide)
- [Configuration](#-configuration)
- [How to Use](#-how-to-use)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)
- [API Key Protection & Environment Setup](#-api-key-protection--environment-setup)

---

## 🚀 Features

- 🔐 **User Authentication**  
  Secure login and registration with role-based access (Student, Senior, Moderator, Admin)

- 👤 **Profile Management**  
  Customizable profiles with academic details and profile pictures

- 📂 **Educational Resources**  
  Upload, download, and access study materials, notes, and curated content

- 💬 **Community Forum**  
  Engage in meaningful discussions with nested comment support

- 📚 **Previous Year Questions (PYQs)**  
  Access and contribute past exam questions for better preparation

- 🎥 **Video Tutorials**  
  Curated educational video content for enhanced learning

- 📱 **Responsive Design**  
  Seamless experience across mobile, tablet, and desktop

---

## 🤖 AI Chatbot & Student Wellness Companion

**Veronica AI Assistant** is now fully integrated with VidyaSathi! Enjoy a modern, Perplexity-style chatbot that supports:

- 🧠 **Mental Wellness**: Talk about stress, motivation, time management, or mental well-being. Veronica offers empathetic, actionable self-care advice and listens without judgment.
- 📘 **Study Help**: Get help with assignments, concepts, and study tips. Veronica can explain topics, suggest study techniques, and keep you on track.
- 🎯 **Goal Motivation**: Receive encouragement, productivity hacks, and time management strategies to help you achieve your academic and personal goals.
- 👥 **Personal Support**: Share your thoughts or worries—Veronica is always here to listen and support you.

**Features:**
- Modern, dark-themed UI inspired by Perplexity.ai  
- Mode selector for Wellness, Study, and Motivation  
- Voice input/output, avatars, and beautiful message formatting  
- Secure API key management (see below)

---

## 🛠️ Technologies Used

- **Frontend**: HTML, CSS, JavaScript, Tailwind CSS  
- **Backend**: PHP  
- **Database**: MySQL  
- **Server Environment**: XAMPP

---

## ⚙️ Installation Guide

1. **Clone the Repository**

   ```bash
   git clone https://github.com/MSAbhishek22/vidyasathi.git


2. **Move to XAMPP's htdocs Directory**

   ```bash
   C:\xampp\htdocs\vidyasathi
   ```

3. **Start Services**

   * Open XAMPP Control Panel
   * Start **Apache** and **MySQL**

4. **Setup the Database**

   * Open **phpMyAdmin** ([http://localhost/phpmyadmin](http://localhost/phpmyadmin))
   * Create a database named **vidyasathi**
   * Import the `.sql` file from the `database/` directory

5. **Access the Application**

   ```bash
   http://localhost/vidyasathi
   ```

---

## 🔧 Configuration

* Database settings are located in:

  * `db.php`
  * `config.php`
* Update credentials according to your local environment if needed.

---

## ✨ How to Use

* Register and create your profile
* Log in and explore the dashboard
* Upload notes, PYQs, and video links
* Engage with the community through forums and comments
* Share resources and collaborate

---

## 🤝 Contributing

We believe **great communities build great platforms**.
Contributions, feature suggestions, and pull requests are warmly welcome! 💬

To contribute:

* Fork the project
* Create your feature branch (`git checkout -b feature/FeatureName`)
* Commit your changes (`git commit -m 'Add FeatureName'`)
* Push to the branch (`git push origin feature/FeatureName`)
* Open a Pull Request 🚀

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

## 📬 Contact

For any queries, ideas, or collaborations, reach out at:
📧 **[msabhishekanni10@gmail.com](mailto:msabhishekanni10@gmail.com)**

---

## 🔒 API Key Protection & Environment Setup

**Never expose your API key in code or on GitHub!**

* The chatbot backend loads the API key from an environment variable using a `.env` file (see `.gitignore`)

* To run locally, create a `.env` file in your project root:

  ```
  GROQ_API_KEY=your_real_api_key_here
  ```

* The `.env` file is ignored by git and will not be pushed to GitHub.

---

© 2025 VidyaSathi | All Rights Reserved.





