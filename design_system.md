# FluxSSH Design System Specification

## 1. UI Style Directions

### Option A: "Neo Terminal Minimalism" (Recommended)
**Philosophy**: A direct evolution of the command line. Brutalist but refined.
**Color Psychology**: High contrast, serious, trustworthy. Dominant blacks and whites with a single electric primary color (Electric Indigo or Hyper Blue).
**Layout**: Dense, grid-based, information-heavy but legible.
**Surfaces**: Flat, matte backgrounds. Zero blur. 1px sharp borders.
**Elevation**: No drop shadows. Depth is conveyed through borders and grayscale layering.

### Option B: "Glass Matrix"
**Philosophy**: Modern, fluid, and translucent. Inspired by macOS and iOS interfaces but adapted for developer tools.
**Color Psychology**: Airy, sophisticated, futuristic.
**Layout**: Floating panels, generous padding.
**Surfaces**: Heavy use of backdrop-blur (glassmorphism), noise textures, and gradients.
**Elevation**: Soft, colored shadows (glows) to indicate active states.

### Option C: "Neutral Velocity"
**Philosophy**: Invisible UI. The content is the interface. Similar to Linear/Vercel.
**Color Psychology**: Warm grays (stone/zinc), muted accents.
**Layout**: Centered, focused, ample whitespace.
**Surfaces**: Subtle gradients, off-white/off-black backgrounds.
**Elevation**: Crisp, realistic shadows.

---

## 2. Full Page Designs

### Dashboard
**Layout**: 3-column grid (Sidebar, Main Content, Activity Feed/Quick Actions).
**Header**: "Good morning, [User]". Quick stats (Active Servers, Uptime, Recent Alerts) in a horizontal strip.
**Main Content**:
- **Server Health Grid**: Small cards showing CPU/RAM usage sparklines.
- **Recent Commands**: A terminal-like list of recent SSH sessions.
**Right Panel**: Notification stream and "Quick Connect" input.

### Server Detail Page
**Layout**: Master-detail view.
**Header**: Server Name, IP, OS Icon, Status Badge (pulsing), "Connect" button (Primary).
**Tabs**: Overview, Console, Logs, Settings.
**Overview Tab**:
- **Real-time Metrics**: Large graphs for CPU, RAM, Disk, Network.
- **Process List**: Sortable table of top processes.
- **Docker Containers**: List of active containers with status.

### Create Server Modal
**Design**: Centered, focused modal. No distractions.
**Steps**:
1.  **Provider**: Grid of logos (AWS, DigitalOcean, etc.).
2.  **Region**: Map or list selection.
3.  **Size**: Card selection with price/specs.
4.  **Name & Tags**: Simple inputs.
**Footer**: Total cost estimate and "Launch" button.

### Settings Page
**Layout**: Sidebar navigation for settings categories (Profile, API, Team, Billing).
**Content**:
- **Profile**: Avatar upload, Name, Email.
- **API Keys**: List of keys with "Copy" and "Revoke" actions. Obfuscated secrets.
- **Theme**: Segmented control (Light / Dark / System).

---

## 3. Light Mode + Dark Mode

### Light Mode ("Paper & Ink")
-   **Background**: `oklch(0.985 0 0)` (Zinc 50)
-   **Surface**: `oklch(1 0 0)` (White)
-   **Border**: `oklch(0.92 0 0)` (Zinc 200)
-   **Text**: `oklch(0.2 0 0)` (Zinc 900)
-   **Shadows**: Crisp, low-blur shadows `0 2px 4px -1px rgba(0,0,0,0.05)`.

### Dark Mode ("Deep Space")
-   **Background**: `oklch(0.14 0 0)` (Zinc 950)
-   **Surface**: `oklch(0.17 0 0)` (Zinc 925 - custom)
-   **Border**: `oklch(0.25 0 0)` (Zinc 800)
-   **Text**: `oklch(0.95 0 0)` (Zinc 100)
-   **Accents**: Luminous, neon-like visibility against dark backgrounds.

---

## 4. Color System (Token-based)

Using OKLCH for perceptual uniformity.

### Base Colors
| Token | Light Value | Dark Value | Usage |
| :--- | :--- | :--- | :--- |
| `--bg-app` | `oklch(0.99 0 0)` | `oklch(0.12 0.01 260)` | Main application background |
| `--bg-surface` | `oklch(1 0 0)` | `oklch(0.15 0.01 260)` | Cards, panels, modals |
| `--bg-surface-alt` | `oklch(0.97 0 0)` | `oklch(0.18 0.01 260)` | Secondary backgrounds, headers |
| `--border-subtle` | `oklch(0.92 0 0)` | `oklch(0.22 0 0)` | Dividers, subtle borders |
| `--border-strong` | `oklch(0.85 0 0)` | `oklch(0.30 0 0)` | Input borders, active states |

### Text Colors
| Token | Light Value | Dark Value | Usage |
| :--- | :--- | :--- | :--- |
| `--text-primary` | `oklch(0.15 0 0)` | `oklch(0.98 0 0)` | Headings, main text |
| `--text-secondary` | `oklch(0.45 0 0)` | `oklch(0.65 0 0)` | Subtitles, labels |
| `--text-tertiary` | `oklch(0.65 0 0)` | `oklch(0.45 0 0)` | Placeholders, disabled text |

### Brand / Accent (Indigo)
| Token | Value | Usage |
| :--- | :--- | :--- |
| `--color-primary-500` | `oklch(0.62 0.22 265)` | Primary buttons, active states |
| `--color-primary-600` | `oklch(0.55 0.22 265)` | Hover states |
| `--color-primary-subtle` | `oklch(0.95 0.05 265)` | Backgrounds for selected items |

### Semantic Colors
| Token | Value | Usage |
| :--- | :--- | :--- |
| `--color-danger` | `oklch(0.6 0.2 25)` | Errors, delete actions |
| `--color-success` | `oklch(0.65 0.18 150)` | Online status, success toasts |
| `--color-warning` | `oklch(0.75 0.15 85)` | Alerts, warnings |
| `--bg-code` | `oklch(0.13 0.02 260)` | Terminal/Code block background (Always dark) |

---

## 5. Typography System

**Font Family**: `Inter` (Variable font recommended)
**Monospace**: `JetBrains Mono` or `Fira Code`

| Role | Size | Line Height | Weight | Tracking |
| :--- | :--- | :--- | :--- | :--- |
| **Display** | 32px | 1.1 | Bold (700) | -0.02em |
| **H1 (Page Title)** | 24px | 1.2 | SemiBold (600) | -0.01em |
| **H2 (Section)** | 18px | 1.3 | Medium (500) | -0.01em |
| **H3 (Card Title)** | 16px | 1.4 | Medium (500) | 0 |
| **Body** | 14px | 1.5 | Regular (400) | 0 |
| **Small** | 13px | 1.5 | Regular (400) | 0 |
| **Caption** | 12px | 1.4 | Medium (500) | 0.01em |
| **Mono** | 13px | 1.6 | Regular (400) | 0 |

---

## 6. Component Library Mapping (Flux UI)

| Component | Flux UI Equivalent | Tailwind Classes | Notes |
| :--- | :--- | :--- | :--- |
| **Primary Button** | `<flux:button variant="primary">` | `bg-primary-500 hover:bg-primary-600 text-white shadow-sm rounded-md px-4 py-2 font-medium transition-all` | Use for main actions only. |
| **Secondary Button** | `<flux:button variant="subtle">` | `bg-surface-alt hover:bg-zinc-200 dark:hover:bg-zinc-800 text-primary rounded-md px-4 py-2 font-medium` | Use for "Cancel" or secondary actions. |
| **Card** | `<flux:card>` | `bg-surface border border-border-subtle rounded-xl shadow-sm p-6` | Main container for content. |
| **Input** | `<flux:input>` | `bg-surface border border-border-strong rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500` | |
| **Badge** | `<flux:badge>` | `rounded-full px-2.5 py-0.5 text-xs font-medium` | Use colors for status (Green=Online, Red=Offline). |
| **Navbar** | `<flux:navbar>` | `h-16 border-b border-border-subtle bg-surface/80 backdrop-blur-md sticky top-0 z-50` | |
| **Sidebar** | `<flux:sidebar>` | `w-64 border-r border-border-subtle bg-bg-app h-screen fixed left-0` | |

---

## 7. Wireframes (ASCII)

### Dashboard
```
+-----------------------------------------------------------------------+
|  [Logo] FluxSSH        [Search...]                [User Avatar]       |
+-------------------+---------------------------------------------------+
|  Dashboard        |  Good morning, Alex                               |
|  Servers          |                                                   |
|  Deployments      |  [ + Connect Server ]                             |
|  Settings         |                                                   |
|                   |  +----------------+  +----------------+           |
|                   |  | Active Servers |  | Total Uptime   |           |
|                   |  |      12        |  |    99.9%       |           |
|                   |  +----------------+  +----------------+           |
|                   |                                                   |
|                   |  RECENT SERVERS                                   |
|                   |  +--------------------------------------------+   |
|                   |  | Name       IP            Status    CPU     |   |
|                   |  +--------------------------------------------+   |
|                   |  | prod-01    192.168.1.1   (o) Online 45%    |   |
|                   |  | db-main    10.0.0.5      (o) Online 60%    |   |
|                   |  | staging    10.0.0.8      (x) Down    0%    |   |
|                   |  +--------------------------------------------+   |
|                   |                                                   |
+-------------------+---------------------------------------------------+
```

### Server Detail
```
+-----------------------------------------------------------------------+
|  < Back            prod-01 (192.168.1.1)            [ SSH ] [ Reboot ]|
+-------------------+---------------------------------------------------+
|  Overview         |  +-------------------+  +----------------------+  |
|  Console          |  | CPU Usage         |  | Memory Usage         |  |
|  Logs             |  | [||||||||....] 45%|  | [||||||........] 32% |  |
|  Settings         |  +-------------------+  +----------------------+  |
|                   |                                                   |
|                   |  TERMINAL PREVIEW                                 |
|                   |  +--------------------------------------------+   |
|                   |  | root@prod-01:~# htop                       |   |
|                   |  | [ PID USER PRI NI VIRT RES SHR S %CPU ]    |   |
|                   |  | ...                                        |   |
|                   |  +--------------------------------------------+   |
+-------------------+---------------------------------------------------+
```

### Create Server Modal
```
+-------------------------------------------------------+
|  Create New Server                                [X] |
+-------------------------------------------------------+
|  1. Select Provider                                   |
|  [ (AWS) ]  [ (DO) ]  [ (Vultr) ]  [ (Linode) ]       |
|                                                       |
|  2. Select Region                                     |
|  [ US East ] [ US West ] [ EU Central ]               |
|                                                       |
|  3. Server Size                                       |
|  +--------------+  +--------------+                   |
|  | Standard     |  | Pro          |                   |
|  | 2GB / 1 CPU  |  | 4GB / 2 CPU  |                   |
|  | $10/mo       |  | $20/mo       |                   |
|  +--------------+  +--------------+                   |
|                                                       |
|  Name: [ my-server-01       ]                         |
|                                                       |
|  ---------------------------------------------------  |
|  Est: $10/mo                    [ Cancel ] [ Launch ] |
+-------------------------------------------------------+
```

### Settings Page
```
+-----------------------------------------------------------------------+
|  Settings                                                             |
+-------------------+---------------------------------------------------+
|  General          |  Profile Settings                                 |
|  API Keys         |                                                   |
|  Team             |  [ Avatar ]  [ Upload New ]                       |
|  Billing          |                                                   |
|                   |  Full Name                                        |
|                   |  [ Alex Johnson       ]                           |
|                   |                                                   |
|                   |  Email Address                                    |
|                   |  [ alex@example.com   ]                           |
|                   |                                                   |
|                   |  Theme Preference                                 |
|                   |  ( ) Light  (o) Dark  ( ) System                  |
|                   |                                                   |
|                   |  [ Save Changes ]                                 |
+-------------------+---------------------------------------------------+
```

---

## 8. Motion & Interaction Guidelines

### Hover States
-   **Cards**: Subtle lift `transform: translateY(-2px)` and shadow increase `shadow-md`.
-   **Buttons**: Brightness increase (Light mode) or Glow effect (Dark mode).
-   **Table Rows**: Background highlight `bg-zinc-50 dark:bg-zinc-800/50`.

### Focus States
-   **Inputs**: 2px ring with `ring-primary-500/30` (transparent outline) + border color change. Never use default browser focus.

### Micro-animations
-   **Page Load**: Staggered fade-in for content blocks (`animate-enter`).
-   **Modals**: Quick zoom-in `scale-95` -> `scale-100` with opacity fade.
-   **Charts**: Line charts should draw from left to right on load.
-   **Status Indicators**: Pulse animation for "Online" green dots.

### Loading States
-   **Skeletons**: Shimmering gray blocks `bg-zinc-200 dark:bg-zinc-800` with `animate-pulse`. Match the exact shape of the content loading.
