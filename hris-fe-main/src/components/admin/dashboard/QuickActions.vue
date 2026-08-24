<script setup lang="ts">
import { computed } from "vue";
import { RouterLink } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { can } from "@/helpers/permissionHelper";
import {
  UserPlusIcon,
  UsersIcon,
  BanknoteIcon,
  CalendarPlusIcon,
  ClockIcon,
  CalendarIcon,
  ReceiptIcon,
} from "lucide-vue-next";

const authStore = useAuthStore();

const isEmployee = computed(() => {
  return authStore.user?.roles?.some((role: any) => role === "employee");
});

// Dynamic actions list based on RBAC permissions
const actions = computed(() => {
  const list = [];

  if (isEmployee.value) {
    if (can("attendance-check-in") || can("attendance-check-out")) {
      list.push({
        label: "Clock In / Out",
        icon: ClockIcon,
        to: { name: "employee.attendance.clock" },
      });
    }

    if (can("attendance-my-attendances")) {
      list.push({
        label: "My Attendance",
        icon: CalendarIcon,
        to: { name: "employee.attendance.my-attendances" },
      });
    }

    list.push({
      label: "My Payslips",
      icon: ReceiptIcon,
      to: { name: "employee.payslips" },
    });

    if (can("team-view")) {
      list.push({
        label: "My Team",
        icon: UsersIcon,
        to: { name: "employee.team" },
      });
    }
  } else {
    // Manager, HR, and Finance
    if (can("employee-create")) {
      list.push({
        label: "Add Employee",
        icon: UserPlusIcon,
        to: { name: "admin.employees.create" },
      });
    }

    if (can("team-create")) {
      list.push({
        label: "Create New Team",
        icon: UsersIcon,
        to: { name: "admin.team.create" },
      });
    }

    if (can("payroll-create")) {
      list.push({
        label: "Process Payroll",
        icon: BanknoteIcon,
        to: { name: "admin.payroll.create" },
      });
    }

    if (can("attendance-list")) {
      list.push({
        label: "Review Attendance",
        icon: CalendarPlusIcon,
        to: { name: "admin.attendances" },
      });
    }
  }

  return list.slice(0, 4);
});
</script>

<template>
  <!-- Quick Actions Card (spans 2 rows on the right) -->
  <div
    class="lg:row-span-2 bg-white border border-[#DCDEDD] rounded-[20px] hover:border-[#0C51D9] hover:border-2 transition-all duration-300 p-5"
  >
    <h3 class="text-brand-dark text-lg font-bold mb-4">Quick Actions</h3>
    <div class="space-y-3">
      <template v-for="(action, index) in actions" :key="index">
        <!-- Primary Action (First item) -->
        <RouterLink
          v-if="index === 0"
          :to="action.to"
          class="btn-secondary w-full text-left rounded-[12px] border border-[#2151A0] hover:brightness-110 focus:ring-2 focus:ring-[#0C51D9] transition-all duration-300 blue-gradient blue-btn-shadow px-4 py-3 flex items-center gap-2"
        >
          <component :is="action.icon" class="w-4 h-4 text-white" />
          <span class="text-brand-white text-sm font-semibold">{{ action.label }}</span>
        </RouterLink>

        <!-- Secondary Actions -->
        <RouterLink
          v-else
          :to="action.to"
          class="btn-secondary w-full text-left border border-[#DCDEDD] rounded-[16px] hover:border-[#0C51D9] hover:border-2 hover:rounded-[12px] focus:border-[#0C51D9] focus:border-2 focus:rounded-[12px] focus:bg-white transition-all duration-300 px-4 py-3 flex items-center gap-2"
        >
          <component :is="action.icon" class="w-4 h-4 text-gray-600" />
          <span class="text-brand-dark text-sm font-medium">{{ action.label }}</span>
        </RouterLink>
      </template>
    </div>
  </div>
</template>
