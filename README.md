# Contributor Photo Gallery
[![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-blue.svg)](https://wordpress.org) [![Version](https://img.shields.io/badge/Version-2.5.1-green.svg)](https://github.com/askhellosatya/contributor-photo-gallery/releases)  [![License](https://img.shields.io/badge/License-GPL%20v2%2B-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

Tags: gallery, photography, portfolio, shortcode, responsive  
Requires at least: 5.8  
Tested up to: 7.0  
Stable tag: 2.5.1  

Showcase your [WordPress.org/photos](https://wordpress.org/photos/) contributions in beautiful, responsive, and SEO-friendly galleries.  
Built for photographers, agencies, and community contributors who want polished photo portfolios with minimal setup and full styling control.  

---

## 📑 Table of Contents
- [✨ Features](#-features)
- [🚀 Quick Start](#-quick-start)
  - [Step 1: Install & Activate](#step-1-install--activate)
  - [Step 2: Configure Your Profile](#step-2-configure-your-profile)
  - [Step 3: Display Your Gallery](#step-3-display-your-gallery)
- [📸 Shortcodes](#-shortcodes)
- [🎯 Perfect For](#-perfect-for)
- [⚙️ Settings (Admin Panel)](#️-settings-admin-panel)
- [🎨 Frontend Classes & CSS Variables](#-frontend-classes--css-variables)
- [🔍 Accessibility & SEO](#-accessibility--seo)
- [📋 Changelog](#-changelog)
- [🤝 Contributors](#-contributors)
- [💡 Contributing & Support](#-contributing--support)
- [🤝 Development Setup](#-development-setup)
- [📄 License](#-license)
- [💝 Show Your Appreciation](#-show-your-appreciation)

---

## ✨ Features

- Fetches photos you contributed to WordPress.org/photos and renders them on your site.  
- Multiple gallery/card styles: **Modern, Polaroid, Circle, Fixed Height**.  
- Fine-grained styling: background color, border style/width/color, shadow presets, caption text color.  
- Live admin preview with auto-refresh — style and see changes instantly.  
- **Primary shortcode:** `[cp_gallery]` (recommended). Legacy `[wpcontrib_photos]` still supported.  
- Performance-minded: smart caching + lazy loading.  
- SEO-friendly markup with `alt` attributes, semantic links, and accessible captions.  

---

## 🚀 Quick Start

### Step 1: Install & Activate
1. Go to **Plugins → Add New** in your WordPress admin.  
2. Search for **"Contributor Photo Gallery"** or upload the ZIP from GitHub.  
3. Click **Install** → **Activate**.  

---

### Step 2: Configure Your Profile
1. Go to **Settings → Contributor Photo Gallery**.  
2. Enter your **WordPress.org numeric User ID** (e.g., `21053005`).  
3. Adjust styling: card type, captions, borders, shadows, colors.  
4. Save changes and preview instantly in the admin.  

![Settings Page](https://github.com/user-attachments/assets/cfa8267f-d50a-45b8-a4a5-8842cf6d454a)

---

### Step 3: Display Your Gallery
Add this shortcode anywhere:  

```text
[cp_gallery]
```

![Gallery Preview](https://github.com/user-attachments/assets/30acdc34-77ab-4011-8607-79a23852bbf7)

---

## 📸 Shortcodes

### `[cp_gallery]` (primary, recommended)  
**Attributes:**  
- `per_page` — photos per gallery page (1–50). Example: `per_page="12"`  
- `columns` — grid columns (1–6). Example: `columns="3"`  
- `user_id` — override saved numeric ID. Example: `user_id="21053005"`  

## 📖 Usage Examples

| Use Case | Shortcode | Description |
|----------|-----------|-------------|
| 🏆 **Portfolio Showcase** | `[cp_gallery per_page="20" columns="4"]` | Professional portfolios and showcases |
| 👤 **About Page** | `[cp_gallery per_page="12" columns="3"]` | Personal branding sections |
| ✍️ **Blog Enhancement** | `[cp_gallery per_page="6" columns="2"]` | Content creation and articles |
| 📱 **Sidebar Widget** | `[cp_gallery per_page="4" columns="1"]` | Compact sidebar displays |
| ⚙️ **Default** | `[cp_gallery]` | Uses your configured settings |


Uses your configured default settings.

### `[wpcontrib_photos]` (legacy)  
- Fully supported to preserve older galleries.  
- Internally maps to the same handler as `[cp_gallery]`.  

---

## 🎯 Perfect For

- **Photographers**: portfolio sites, showcasing community involvement.  
- **Agencies & Developers**: team contributions beyond code.  
- **Speakers & Community Members**: add credibility for WordCamps, profiles, and blogs.  
- **Bloggers & Content Creators**: enhance posts with authentic WordPress.org photography.  

---

## ⚙️ Settings (Admin Panel)

- **User ID**: your WordPress.org numeric contributor ID.  
- **Photos per gallery**: 1–50 (default: 12).  
- **Grid columns**: 1–6 with live preview.  
- **Card styling**: Modern, Polaroid, Circle, Fixed Height.  
- **Caption settings**: show/hide toggle, caption text color.  
- **Borders & shadows**: full styling controls.  
- **Performance**: caching duration, lazy loading, link behavior.  

---

## 🎨 Frontend Classes & CSS Variables

The plugin outputs easy-to-target CSS classes so you can theme the gallery from your theme or custom CSS.

```css
/* Variables */
--cpg-card-bg
--cpg-card-border
--cpg-card-shadow
--cpg-caption-color

/* Example override */
.cpg-gallery-grid .cpg-photo-content p {
  color: #ffffff !important;
}
```

---

## 🔍 Accessibility & SEO

- Captions double as `alt` text where available.  
- Semantic anchors link back to WordPress.org/photos.  
- Custom caption and card contrast controls for WCAG compliance.  

---

## 📋 Changelog

### 2.5.1 - 
- Fixed: Review notice styling inconsistencies (spacing, alignment, and typography).
- Fixed: Uneven photo card layouts by standardizing aspect ratios and ensuring images scale correctly. [#17](https://github.com/askhellosatya/contributor-photo-gallery/issues/17)
- Fixed: Overlapping and stacking issues where images or pseudo elements could break alignment.
- Changed: Replaced `cpg_` function prefix with `cpglry_` for better clarity and to avoid conflicts.
- Improved: Cross-browser and cross-device reliability of all card styles.
- Improved: Polaroid-style font for vintage feel.

### 2.5.0 —
- New primary shortcode: `[cp_gallery]`.  
- Caption text color option + live preview.  
- Grid styles: Modern, Polaroid, Circle, Fixed Height.  
- Card customization: borders, background, shadows.  
- Auto-refresh live preview.  
- Minimum WordPress: 5.8.  

### 2.0.0  
- Major UI/UX overhaul with modern design.  
- Copy-to-clipboard for shortcode examples.  
- Accessibility & mobile enhancements.  

### 1.0.0  
- Initial release. Basic gallery with shortcode.  

---

## 🤝 Contributors

*"The heartbeat of open source: You!"* ✨  

Thanks to all contributors, testers, and reviewers:  

<a href="https://github.com/askhellosatya/contributor-photo-gallery/graphs/contributors">
  <img height="36px" src="https://contrib.rocks/image?repo=askhellosatya/contributor-photo-gallery"/>
</a>  

---

## 💡 Contributing & Support

- Repo: https://github.com/askhellosatya/contributor-photo-gallery  
- Issues: https://github.com/askhellosatya/contributor-photo-gallery/issues  
- Discussions: https://github.com/askhellosatya/contributor-photo-gallery/discussions  

Maintainer: [Satyam Vishwakarma](https://satyamvishwakarma.com) 

---

## 🤝 Development Setup

This repository includes a Docker-based local development environment for contributors.

### Start the development environment

```bash
docker compose up -d
```

After the containers start:

* WordPress: http://localhost:8000
* phpMyAdmin: http://localhost:8080

### Need help with Docker?

If you're new to Docker or need help installing Docker Desktop, please refer to the official Docker documentation:

https://docs.docker.com/get-started/

For WordPress plugin contributors, Docker provides a quick way to run a clean WordPress installation with this plugin already mounted and ready for development.

---

## 📄 License

Contributor Photo Gallery is licensed under the **GPL v2 or later** — see [LICENSE](LICENSE).  

---

## 💝 Show Your Appreciation

- ⭐ Star the repo to help others discover it.  
- 📸 Contribute more photos to [WordPress.org/photos](https://wordpress.org/photos/).  
- ☕ [Support development](https://paypal.me/hellosatya).  
