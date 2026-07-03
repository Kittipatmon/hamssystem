---
name: Kumwell HAMS
description: Human Assets Management & Service Building Portal
colors:
  primary: "#b81515"
  neutral-bg: "#ffffff"
  base-100: "#ffffff"
  base-200: "#fafdff"
  base-300: "#f2f5f7"
  text-main: "#191c1e"
typography:
  display:
    fontFamily: "Figtree, sans-serif"
    fontSize: "clamp(2.5rem, 5vw, 3.5rem)"
    fontWeight: 700
    lineHeight: 1.2
  body:
    fontFamily: "Figtree, sans-serif"
    fontSize: "14px"
    fontWeight: 400
    lineHeight: 1.5
rounded:
  sm: "4px"
  md: "8px"
  lg: "16px"
spacing:
  sm: "8px"
  md: "16px"
  lg: "24px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.base-100}"
    rounded: "{rounded.md}"
    padding: "8px 16px"
---

# Design System: Kumwell HAMS

## 1. Overview

**Creative North Star: "The Reliable Dashboard"**

Kumwell HAMS is a professional, trusted, and efficient internal portal designed to manage human assets and support services (e.g., housing request forms, car bookings, and meeting rooms). The design focuses on clean lines, structured data presentation, and clear corporate identity. It rejects messy layouts, neon gradients, heavy dark-mode accents, and excessive glassmorphism.

**Key Characteristics:**
- High usability and visual structure.
- Professional corporate color palette centered on Kumwell Red.
- Accessible contrast ratios and clear typography hierarchy.

## 2. Colors

The color palette is grounded in the corporate branding, featuring a dominant primary red accompanied by functional neutrals that ensure content readability and trust.

### Primary
- **Kumwell Red** (#b81515 / oklch(54.001% 0.25542 262.302)): Used for core brand elements, primary actions, active navigation items, and positive highlights.

### Neutral
- **Base Background** (#ffffff): Used as the primary canvas for views.
- **Surface light** (#fafdff): Used for subtle content blocks and section divisions.
- **Text Main** (#191c1e): High-contrast dark charcoal color for all primary text elements to ensure maximum accessibility.

### Named Rules
**The Red Moderation Rule.** Kumwell Red is used selectively on <= 15% of any page layout to maintain its impact and direct the user's attention.

## 3. Typography

**Display Font:** Figtree (sans-serif fallback)
**Body Font:** Figtree (sans-serif fallback)

### Hierarchy
- **Display** (700, clamp(2.5rem, 5vw, 3.5rem), 1.2): Main hero headlines and system welcome banners.
- **Headline** (600, 1.75rem, 1.25): Major section headers.
- **Title** (600, 1.25rem, 1.3): Cards, tables, and list titles.
- **Body** (400, 14px, 1.5): Standard informational paragraphs, form descriptions, and table entries. Max line length: 75ch.
- **Label** (500, 12px, 1.2): Input headers, helper texts, and button copy.

## 4. Elevation

The system utilizes a hybrid depth approach. Main backgrounds and workspaces are flat, utilizing tonal shifts (base-100 vs base-200) to separate sections, while interactive items (like cards, dropdowns, and buttons) make use of subtle box-shadows to signify interactivity.

### Shadow Vocabulary
- **Card Shadow** (`box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)`): Applied to default cards and layout containers at rest.

### Named Rules
**The Dynamic Response Rule.** Elevation is interactive. Shadows lift or change state primarily on hover, focus, or select events to signal responsiveness to the user's input.

## 5. Components

### Buttons
- **Shape:** Rounded corners (8px radius)
- **Primary:** Background: Kumwell Red (`#b81515`), Text: White (`#ffffff`), Padding: 8px 16px.
- **Hover:** Light transition shifts to highlight or slightly darken color.

### Cards / Containers
- **Corner Style:** Large radius (16px)
- **Background:** White (`#ffffff`) or light tint (`#fafdff`)
- **Border:** Subtle border (1px solid `#e2e8f0`)

### Inputs / Fields
- **Corner Style:** Small radius (4px)
- **Border:** Light stroke (1px solid `#cbd5e1`)
- **Focus:** Highlighted border color matching primary theme colors.

## 6. Do's and Don'ts

### Do:
- **Do** maintain a strict 4.5:1 contrast ratio for all status descriptions and body labels.
- **Do** align form items vertically in a structured, consistent grid.
- **Do** keep action buttons (Primary / Cancel) consistently located across form views.

### Don't:
- **Don't** use neon accent shadows or glowing button edges.
- **Don't** use decorative uppercase wide-tracked Kickers above every section.
- **Don't** mix multiple sans-serif fonts in display headers.
