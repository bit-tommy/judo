# Design System Strategy: Judo Club Raion-ryu

## 1. Overview & Creative North Star: "The Modern Dojo"
This design system moves away from the cluttered, boxed-in aesthetic of traditional martial arts websites toward a philosophy we call **"The Modern Dojo."** 

Just as a physical dojo balances discipline (structure) with movement (energy), this system utilizes high-contrast editorial typography and intentional asymmetry to create a premium, energetic experience. We break the "template" feel by ditching rigid borders in favor of overlapping imagery and tonal layering. The goal is a visual narrative that feels professional for parents but exciting for children, using Japanese-inspired minimalist principles to guide the user's eye toward action.

---

## 2. Color & Tonal Depth
We utilize a palette rooted in tradition but executed with modern sophistication.

### Palette Execution
- **Primary Mastery:** `primary` (#620000) and `primary_container` (#8c0000) are reserved for moments of high impact. Use the deep red for hero headlines and primary CTAs to evoke the "Raion" (Lion) spirit.
- **Surface Hierarchy (The No-Line Rule):** To maintain an editorial feel, **1px solid borders are prohibited.** Sectioning must be achieved through background shifts. Place a `surface_container_low` section against a `surface` background to define boundaries.
- **Glassmorphism:** For floating navigation or modal overlays, use `surface` at 80% opacity with a `backdrop-blur` of 12px. This ensures the energetic imagery of the club remains visible, creating a sense of depth and integration.
- **Signature Textures:** Incorporate a subtle "Washi paper" grain or a low-opacity `primary` to `primary_container` gradient in the background of hero sections to provide a tactile, high-end finish.

---

## 3. Typography: Editorial Authority
The type system pairs the technical precision of **Space Grotesk** with the friendly accessibility of **Plus Jakarta Sans**.

- **Display & Headline (Space Grotesk):** Used for big, bold statements. `display-lg` (3.5rem) should be used for hero titles, often with "asymmetric" placement (e.g., overlapping a high-quality action photo) to break the grid.
- **Title & Body (Plus Jakarta Sans):** Chosen for its warmth and legibility. `title-lg` (1.375rem) handles subheadings for children's programs, while `body-lg` (1rem) provides a comfortable reading experience for parents researching the curriculum.
- **Visual Rhythm:** Use `on_primary_fixed_variant` (#920603) for selective text highlights within body copy to draw attention without the "loudness" of a button.

---

## 4. Elevation & Depth: The Layering Principle
We convey importance through **Tonal Layering** rather than traditional structural lines or heavy drop shadows.

- **The Stacking Rule:** Create a soft, natural lift by placing a `surface_container_lowest` (#ffffff) card on a `surface_container_low` (#f3f3f3) background.
- **Ambient Shadows:** When a "floating" element is mandatory (e.g., a "Sign Up" FAB), use a shadow with a 32px blur at 6% opacity, tinted with `on_surface`. It should feel like a soft glow, not a hard shadow.
- **Ghost Borders:** For form fields or secondary buttons where a container is required, use `outline_variant` at 15% opacity. Never use 100% opaque borders.
- **Asymmetric Overlaps:** Elements should "bleed" into other sections. A high-quality image of a judoka should partially overlap a `surface_container` and a `surface` section to create a dynamic, non-linear flow.

---

## 5. Components

### Buttons
- **Primary:** High-contrast `primary_container` (#8C0000) background with `on_primary` text. Use `roundedness-md` (0.375rem) for a professional yet approachable feel.
- **Secondary/Ghost:** `surface_container_highest` background or a "Ghost Border" (15% opacity `outline`).

### Cards & Content Modules
- **Rule:** Forbid divider lines. Use `spacing-12` (3rem) of vertical white space to separate content blocks.
- **Program Cards:** Use `surface_container_low`. On hover, transition to `surface_container_high` with a subtle 2px upward "lift" (Y-axis translation) rather than a heavy shadow.

### Imagery & Iconography
- **Hero Imagery:** Use high-shutter-speed photography of children in white gi’s against dark backgrounds to make the `primary` red accents pop.
- **Iconography:** Use custom, thin-stroke Japanese-inspired icons (e.g., a stylized lion or tatami mat pattern) using the `tertiary` (#00304c) color for a subtle "Black Belt" accent.

### Inputs & Forms
- **Fields:** Use `surface_container_lowest` with a "Ghost Border." Focus states should transition the border to `surface_tint` (#b52619) at 40% opacity.

---

## 6. Do’s and Don’ts

### Do:
- **Use Whitespace as a Tool:** Give elements room to breathe. Use `spacing-20` (5rem) between major sections to let the "Modern Dojo" aesthetic feel calm and focused.
- **Intentional Asymmetry:** Offset text blocks from center-aligned images to create a "magazine" feel.
- **Focus on Imagery:** High-quality, authentic photos of the Raion-ryu students are your primary design assets. Treat them like art, not thumbnails.

### Don’t:
- **No Divider Lines:** Never use a horizontal rule `<hr>` or 1px borders to separate content. Let color shifts do the work.
- **Avoid Default "Web" Blues:** Only use the `tertiary` (#00304c) blue for very specific UI accents or links to maintain the "Deep Red/Black/White" brand soul.
- **No Hard Shadows:** Avoid the standard "card-shadow" look. If it looks like a generic bootstrap site, it needs more tonal layering and less shadow.
- **Don't Over-Decorate:** Subtle Japanese textures (like a light brush stroke) are enough. Avoid clichéd "oriental" fonts or heavy-handed motifs. Keep it contemporary.