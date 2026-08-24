---
name: hris-fe
description: Architecture, design system guidelines, UI conventions, Pinia store management, and development standards for the HRIS Vue 3 frontend application (hris-fe-main). Use when creating or modifying Vue components, views, routes, Pinia stores, forms, or UI layouts.
---

# HRIS Frontend Development Guide (`hris-fe-main`)

This skill provides architectural rules, component patterns, state management standards, and design system guidelines for the **SenjaHRIS Frontend** application.

---

## 1. Technology Stack & Environment

- **Framework**: Vue 3 (`<script setup lang="ts">` / Composition API).
- **Build Tool**: Vite 5 (configured for Node 18+ compatibility).
- **CSS Framework**: Tailwind CSS v3 & `@tailwindcss/cli`.
- **State Management**: Pinia 3.
- **Routing**: Vue Router 4.
- **HTTP Client**: Axios with global Bearer token interceptor (`src/plugins/axios.js`).
- **Icons**: `lucide-vue-next`.
- **Charts & Reports**: `vue3-apexcharts`, `apexcharts`, `jspdf`, `jspdf-autotable`.
- **Cookies**: `js-cookie` (auth token key: `'token'`).

---

## 2. Directory Structure

```text
hris-fe-main/
├── src/
│   ├── assets/                # Custom CSS (input.css, main.css)
│   ├── components/
│   │   ├── admin/             # Dashboard, Team, Employee, Project widgets & tables
│   │   ├── common/            # Reusable UI (Alert.vue, Input.vue, Modals, Pagination)
│   │   └── employee/          # Employee specific components
│   ├── helpers/               # errorHelper.js, permissionHelper.js (can, canOneOf)
│   ├── layouts/               # Admin.vue, Auth.vue, EmployeeCreateLayout.vue
│   ├── plugins/               # axios.js (Axios instance configured with baseURL)
│   ├── router/                # Modular routes (index.js, team.js, employee.js, project.js, etc.)
│   ├── stores/                # Pinia stores (auth, team, employee, project, task, attendance, payroll)
│   ├── utils/                 # Formatting, date utilities (luxon)
│   ├── views/
│   │   ├── admin/             # Admin/Manager views (Dashboard, TeamList, EmployeeList, etc.)
│   │   ├── auth/              # Login.vue
│   │   └── employee/          # Employee portal views (MyAttendance, MyPayslips, etc.)
│   ├── App.vue
│   └── main.js
├── .env                       # VITE_API_BASE_URL=http://localhost:8000/api/v1
└── vite.config.js             # Alias '@' mapped to './src'
```

---

## 3. Design System & UI Conventions

### Typography & Colors
- **Font Family**: `Plus Jakarta Sans` (`font-plus-jakarta-sans`).
- **Primary Color**: `#0C51D9` (Tailwind `primary-600` / `blue-gradient`).
- **Borders & Dividers**: `#DCDEDD` (Border radius: `rounded-[16px]` for cards, `rounded-[20px]` for nav links, `rounded-[8px]` for buttons).
- **Text Colors**: `#111827` (Dark text `text-brand-dark`), `#6B7280` (Muted text `text-brand-light` / `text-gray-500`).

### Standard Button Styling
- **Primary Action**:
  ```html
  <button class="btn-primary rounded-[8px] border border-[#2151A0] hover:brightness-110 focus:ring-2 focus:ring-[#0C51D9] transition-all duration-300 blue-gradient blue-btn-shadow px-4 py-3 text-white text-[14px] font-semibold flex items-center justify-center gap-2">
    <span>Submit</span>
  </button>
  ```

### Form & Input Guidelines
- Always wrap forms with `@submit.prevent="handleSubmit"`.
- Use `<Input>` component from `@/components/common/form/Input.vue` with leading icon slots.
- Disable submit buttons during async actions with `:disabled="loading"`.

---

## 4. Role-Based Access Control (RBAC) in UI

Use the `can()` helper from `@/helpers/permissionHelper` to guard UI elements:

```vue
<script setup>
import { can, canOneOf } from "@/helpers/permissionHelper";
</script>

<template>
  <!-- Show button only if user has permission -->
  <button v-if="can('team-create')" @click="openCreateModal">
    Create Team
  </button>

  <!-- Show menu if user has any of the listed permissions -->
  <div v-if="canOneOf(['attendance-check-in', 'attendance-check-out'])">
    ...
  </div>
</template>
```

---

## 5. Pinia Store Pattern

Standard structure for stores:

```javascript
import { defineStore } from 'pinia';
import { axiosInstance } from '@/plugins/axios';
import { handleError } from '@/helpers/errorHelper';

export const useExampleStore = defineStore('example', {
  state: () => ({
    items: [],
    item: null,
    meta: { current_page: 1, last_page: 1, total: 0, per_page: 10 },
    loading: false,
    error: null,
    success: null,
  }),
  actions: {
    async fetchItems(params) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axiosInstance.get('/items/all/paginated', { params });
        this.items = response.data.data.data;
        this.meta = response.data.data.meta;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
  },
});
```

---

## 6. Routing & Navigation Conventions

- Sub-routes are kept in modular files under `src/router/` (`team.js`, `employee.js`, `project.js`, etc.).
- Static and action routes (like `/create` or `/edit/:id`) **MUST** be defined **BEFORE** dynamic parameter routes (like `/:id`).
- All protected routes should have `meta: { requiresAuth: true }`.

---

## 7. Development & Build Commands

Always execute from `hris-fe-main/`:

- **Run development server**:
  ```powershell
  npm run dev
  ```
- **Run production build**:
  ```powershell
  npm run build
  ```
- **Watch Tailwind CSS**:
  ```powershell
  npm run tw:watch
  ```
