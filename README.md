
# 📚 VidyaSathi – Empowering the Educational Community

![Made with React](https://img.shields.io/badge/Made%20with-React-blue)
![TypeScript](https://img.shields.io/badge/TypeScript-blue)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-38B2AC)
![shadcn/ui](https://img.shields.io/badge/UI-shadcn/ui-black)
![Supabase](https://img.shields.io/badge/Supabase-3ECF8E)
![PostgreSQL](https://img.shields.io/badge/Database-PostgreSQL-336791?logo=postgresql)
![Vite](https://img.shields.io/badge/Build-Vite-646cff?logo=vite)
![License MIT](https://img.shields.io/badge/License-MIT-green)
![Open Source Love](https://img.shields.io/badge/Open%20Source-%E2%9D%A4-red)
![Contributions Welcome](https://img.shields.io/badge/Contributions-Welcome-brightgreen)

---

**VidyaSathi** is a dynamic educational platform that brings students and educators together in one vibrant online community. Whether you're studying for exams, sharing knowledge, or seeking help, VidyaSathi has your back!

---

## 🚀 Live Demo

🔗 **Live Site**: [Visit VidyaSathi](https://vidyasathi.vercel.app/)

---

## 📑 Table of Contents

- [Features](#-features)
- [AI Chatbot & Student Wellness Companion](#-ai-chatbot--student-wellness-companion)
- [Technologies Used](#-technologies-used)
- [Installation Guide](#-installation-guide)
- [Configuration](#-configuration)
- [API Key Protection & Environment Setup](#-api-key-protection--environment-setup)
- [How to Use](#-how-to-use)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

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
- Secure API key management via Supabase Edge Functions

---

## 🛠️ Technologies Used

- **Frontend**: React, TypeScript, Tailwind CSS
- **UI Components**: shadcn/ui, Radix UI
- **Backend**: Supabase (Database, Authentication, Edge Functions, Real-time DB)
- **Build Tool**: Vite
- **AI Integration**: Google Gemini (via Supabase Edge Functions)
- **State Management**: TanStack Query
- **Package Manager**: Bun
- **Deployment**: Vercel, GitHub
  
---

## ⚙️ Installation Guide

1. **Clone the Repository**

   ```bash
   git clone https://github.com/yourusername/vidyasathi.git
   cd vidyasathi
   ```

2. **Install Dependencies**

   ```bash
   npm install
   ```

3. **Setup Supabase**

   * Create a new project on [Supabase](https://supabase.com)
   * Copy your project URL and anon key
   * Run the database migrations from the `supabase/migrations/` directory

4. **Environment Configuration**

   * Configure your Supabase credentials in the project settings
   * Set up any required API keys via Supabase secrets (see configuration section below)

5. **Start Development Server**

   ```bash
   npm run dev
   ```

6. **Access the Application**

   ```bash
   http://localhost:5173
   ```

---

## 🔧 Configuration

### Supabase Setup

* Database settings are configured in `src/integrations/supabase/client.ts`
* Authentication and database schemas are defined in the migrations
* Edge functions for AI chatbot are located in `supabase/functions/`

### API Keys

* AI chatbot requires a Gemini API key stored in Supabase secrets
* Configure secrets via Project Settings → Secrets in your Supabase dashboard
* Required secrets: `GEMINI_API_KEY`

---

## 🔒 API Key Protection & Environment Setup

**API keys are securely managed through Supabase!**

* The chatbot uses Supabase Edge Functions to securely handle API requests
* API keys are stored as Supabase secrets and never exposed to the frontend
* To configure API keys:
  1. Go to your Supabase project dashboard
  2. Navigate to Project Settings → Secrets
  3. Add your `GEMINI_API_KEY` secret
  4. The Edge Functions automatically use these secrets securely

* For local development with Supabase CLI:
  ```bash
  supabase secrets set GEMINI_API_KEY=your_api_key_here
  ```

* No `.env` files needed - Supabase handles all environment configuration!

---

## ✨ How to Use

* Register and create your profile
* Log in and explore the dashboard
* Upload notes, PYQs, and video links
* Engage with the community through forums and comments
* Chat with Veronica AI for study help and wellness support
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
📧 **[aayushigoel73@gmail.com](mailto:aayushigoel73@gmail.com)**

---

© 2025 VidyaSathi | All Rights Reserved.
