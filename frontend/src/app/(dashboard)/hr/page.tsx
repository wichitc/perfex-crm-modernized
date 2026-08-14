"use client";

import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { Users, UserCheck, Calendar, Briefcase, Plus, Search } from "lucide-react";

export default function HRPage() {
  const { t } = useTranslation();
  const [searchTerm, setSearchTerm] = useState("");
  const [departmentFilter, setDepartmentFilter] = useState("All");

  const { data: hrData } = useQuery({
    queryKey: ["hr-overview"],
    queryFn: async () => {
      const res = await apiClient.get("/hr/overview");
      return res.data;
    },
  });

  const stats = hrData?.stats || {
    totalEmployees: 64,
    activeStaff: 61,
    onLeave: 3,
    openRequisitions: 5,
  };

  const employeesList = hrData?.employees || [
    { id: "EMP-001", name: "Somchai Jaidee", department: "Engineering", position: "Lead Architect", type: "Full-Time", salary: "฿120,000", status: "Active", email: "somchai@novixacrm.com" },
    { id: "EMP-002", name: "Ananya Srisuk", department: "Human Resources", position: "HR Manager", type: "Full-Time", salary: "฿85,000", status: "Active", email: "ananya@novixacrm.com" },
    { id: "EMP-003", name: "Kittisak Vong", department: "Sales & Business", position: "Account Director", type: "Full-Time", salary: "฿105,000", status: "On Leave", email: "kittisak@novixacrm.com" },
  ];

  const filteredEmployees = employeesList.filter((emp: any) => {
    const matchesSearch =
      emp.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      emp.id.toLowerCase().includes(searchTerm.toLowerCase()) ||
      emp.position.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesDept = departmentFilter === "All" || emp.department === departmentFilter;
    return matchesSearch && matchesDept;
  });

  return (
    <div className="space-y-8">
      {/* Top Banner */}
      <div className="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-slate-900/90 to-slate-950 p-6 lg:p-8 border border-slate-800/80 shadow-2xl">
        <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div className="space-y-2">
            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold">
              <UserCheck className="h-3.5 w-3.5" /> Human Resources Management (HRM)
            </div>
            <h1 className="text-2xl lg:text-3xl font-extrabold text-white tracking-tight">
              การบริหารทรัพยากรบุคคล (HR & Staff Directory)
            </h1>
            <p className="text-sm text-slate-400 max-w-xl">
              จัดการข้อมูลพนักงาน ฝ่ายงาน อัตรากำลัง เงินเดือน และการเข้างานในองค์กร
            </p>
          </div>
          <button className="flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white font-bold text-sm shadow-lg shadow-indigo-500/25 transition-all cursor-pointer">
            <Plus className="h-4 w-4" /> เพิ่มข้อมูลพนักงานใหม่
          </button>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div className="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 shadow-xl backdrop-blur-xl">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">พนักงานทั้งหมด</span>
            <div className="p-2.5 rounded-xl bg-indigo-500/10 text-indigo-400">
              <Users className="h-5 w-5" />
            </div>
          </div>
          <p className="text-2xl font-black text-white mt-3">{stats.totalEmployees} คน</p>
          <span className="text-xs text-emerald-400 font-semibold mt-1 inline-block">+2 เดือนนี้</span>
        </div>

        <div className="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 shadow-xl backdrop-blur-xl">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">ปฏิบัติงานปกติ</span>
            <div className="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400">
              <UserCheck className="h-5 w-5" />
            </div>
          </div>
          <p className="text-2xl font-black text-white mt-3">{stats.activeStaff} คน</p>
          <span className="text-xs text-slate-400 font-semibold mt-1 inline-block">95.3% ของทั้งหมด</span>
        </div>

        <div className="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 shadow-xl backdrop-blur-xl">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">อยู่ระหว่างลาพักผ่อน</span>
            <div className="p-2.5 rounded-xl bg-amber-500/10 text-amber-400">
              <Calendar className="h-5 w-5" />
            </div>
          </div>
          <p className="text-2xl font-black text-white mt-3">{stats.onLeave} คน</p>
          <span className="text-xs text-amber-400 font-semibold mt-1 inline-block">ตามอนุมัติ</span>
        </div>

        <div className="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 shadow-xl backdrop-blur-xl">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">ตำแหน่งเปิดรับ</span>
            <div className="p-2.5 rounded-xl bg-purple-500/10 text-purple-400">
              <Briefcase className="h-5 w-5" />
            </div>
          </div>
          <p className="text-2xl font-black text-white mt-3">{stats.openRequisitions} ตำแหน่ง</p>
          <span className="text-xs text-purple-400 font-semibold mt-1 inline-block">กำลังสรรหา</span>
        </div>
      </div>

      {/* Staff Directory Table Section */}
      <div className="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 shadow-xl backdrop-blur-xl space-y-6">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h2 className="text-lg font-bold text-white">สมุดรายนามพนักงาน (Staff Directory)</h2>
            <p className="text-xs text-slate-400">รายชื่อและข้อมูลสังกัดของบุคลากรภายในองค์กร</p>
          </div>

          <div className="flex items-center gap-3">
            <div className="relative">
              <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-500" />
              <input
                type="text"
                placeholder="ค้นหาชื่อ, รหัส, ตำแหน่ง..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="pl-9 pr-4 py-2 rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 w-64"
              />
            </div>
            <select
              value={departmentFilter}
              onChange={(e) => setDepartmentFilter(e.target.value)}
              className="px-3 py-2 rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-300 focus:outline-none focus:border-indigo-500"
            >
              <option value="All">ทุกแผนก (All)</option>
              <option value="Engineering">Engineering</option>
              <option value="Human Resources">Human Resources</option>
              <option value="Sales & Business">Sales & Business</option>
              <option value="Marketing">Marketing</option>
            </select>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs border-collapse">
            <thead>
              <tr className="border-b border-slate-800 bg-slate-950/50 text-slate-400 font-semibold">
                <th className="py-3 px-4">รหัสพนักงาน</th>
                <th className="py-3 px-4">ชื่อ - นามสกุล</th>
                <th className="py-3 px-4">แผนก / ฝ่าย</th>
                <th className="py-3 px-4">ตำแหน่งงาน</th>
                <th className="py-3 px-4">ประเภทสัญญา</th>
                <th className="py-3 px-4">ฐานเงินเดือน</th>
                <th className="py-3 px-4">สถานะ</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800/60 text-slate-300">
              {filteredEmployees.map((emp: any) => (
                <tr key={emp.id} className="hover:bg-slate-800/40 transition-colors">
                  <td className="py-3.5 px-4 font-mono font-bold text-indigo-400">{emp.id}</td>
                  <td className="py-3.5 px-4 font-semibold text-white">{emp.name}</td>
                  <td className="py-3.5 px-4 text-slate-400">{emp.department}</td>
                  <td className="py-3.5 px-4 text-slate-300">{emp.position}</td>
                  <td className="py-3.5 px-4 text-slate-400">{emp.type}</td>
                  <td className="py-3.5 px-4 font-mono font-bold text-white">{emp.salary}</td>
                  <td className="py-3.5 px-4">
                    <span
                      className={`inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold ${
                        emp.status === "Active"
                          ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"
                          : "bg-amber-500/10 text-amber-400 border border-amber-500/20"
                      }`}
                    >
                      {emp.status}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
