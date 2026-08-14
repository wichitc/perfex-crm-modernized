"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import {
  DollarSign,
  Users,
  Receipt,
  GitMerge,
  ArrowUpRight,
  TrendingUp,
  Clock,
  Plus,
} from "lucide-react";

export default function DashboardPage() {
  const { t, formatCurrency } = useTranslation();

  const { data: stats } = useQuery({
    queryKey: ["dashboard-stats"],
    queryFn: async () => {
      const res = await apiClient.get("/dashboard/stats");
      return res.data;
    },
  });

  const { data: tasksList } = useQuery({
    queryKey: ["dashboard-tasks"],
    queryFn: async () => {
      const res = await apiClient.get("/tasks");
      return res.data;
    },
  });

  const revenueChartData = [
    { month: "Jan", revenue: 12400, expenses: 8200 },
    { month: "Feb", revenue: 14500, expenses: 9100 },
    { month: "Mar", revenue: 18200, expenses: 10400 },
    { month: "Apr", revenue: 16800, expenses: 9800 },
    { month: "May", revenue: 21500, expenses: 11200 },
    { month: "Jun", revenue: 24900, expenses: 12800 },
    { month: "Jul", revenue: 28400, expenses: 13500 },
  ];

  const currentStats = stats || {
    totalRevenue: 148920.0,
    revenueChange: "+14.2%",
    activeClients: 48,
    clientsChange: "+4",
    pendingInvoices: 12,
    invoicesAmount: 34500.0,
    openLeads: 27,
    leadsConverted: "68%",
  };

  const tasks = tasksList || [
    { id: 1, name: "Deploy Next.js 16 Multi-Theme Switcher", priority: "High", status: "In Progress", startdate: "2026-07-30", assignee: "Frontend Agent" },
    { id: 2, name: "Verify Client & Lead Management Views", priority: "Medium", status: "Done", startdate: "2026-07-28", assignee: "QA Lead" },
  ];

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Top Banner */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-900/90 to-slate-950 p-6 rounded-3xl border border-slate-800 shadow-xl relative overflow-hidden">
        <div className="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-cyan-500/10 to-transparent pointer-events-none"></div>
        <div>
          <h1 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            {t("dashboard.title")} 👋
          </h1>
          <p className="text-xs text-slate-400 mt-1">
            {t("dashboard.subtitle")}
          </p>
        </div>
        <div className="flex items-center gap-3">
          <a
            href="/invoices"
            className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/25 transition-all cursor-pointer"
          >
            <Plus className="h-4 w-4" /> {t("invoice.addNew")}
          </a>
        </div>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg relative overflow-hidden group hover:border-slate-700 transition-all">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-slate-400">
              {t("dashboard.stat.totalRevenue")}
            </span>
            <div className="h-9 w-9 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/20">
              <DollarSign className="h-5 w-5" />
            </div>
          </div>
          <div className="mt-4">
            <h3 className="text-2xl font-black text-white">{formatCurrency(currentStats.totalRevenue)}</h3>
            <span className="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 mt-1">
              <TrendingUp className="h-3.5 w-3.5" /> {currentStats.revenueChange} vs last month
            </span>
          </div>
        </div>

        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg relative overflow-hidden group hover:border-slate-700 transition-all">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-slate-400">
              {t("dashboard.stat.activeClients")}
            </span>
            <div className="h-9 w-9 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20">
              <Users className="h-5 w-5" />
            </div>
          </div>
          <div className="mt-4">
            <h3 className="text-2xl font-black text-white">{currentStats.activeClients}</h3>
            <span className="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 mt-1">
              <ArrowUpRight className="h-3.5 w-3.5" /> {currentStats.clientsChange} new this month
            </span>
          </div>
        </div>

        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg relative overflow-hidden group hover:border-slate-700 transition-all">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-slate-400">
              {t("dashboard.stat.pendingInvoices")}
            </span>
            <div className="h-9 w-9 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
              <Receipt className="h-5 w-5" />
            </div>
          </div>
          <div className="mt-4">
            <h3 className="text-2xl font-black text-white">{currentStats.pendingInvoices}</h3>
            <span className="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 mt-1">
              Totaling {formatCurrency(currentStats.invoicesAmount)}
            </span>
          </div>
        </div>

        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg relative overflow-hidden group hover:border-slate-700 transition-all">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-slate-400">
              {t("dashboard.stat.convertedLeads")}
            </span>
            <div className="h-9 w-9 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center border border-purple-500/20">
              <GitMerge className="h-5 w-5" />
            </div>
          </div>
          <div className="mt-4">
            <h3 className="text-2xl font-black text-white">{currentStats.openLeads}</h3>
            <span className="inline-flex items-center gap-1 text-[11px] font-bold text-purple-400 mt-1">
              {currentStats.leadsConverted} Win Conversion Rate
            </span>
          </div>
        </div>
      </div>

      {/* Main Grid: Revenue Bar Chart Representation & Task Overview */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Chart Column */}
        <div className="lg:col-span-2 bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl">
          <div className="flex items-center justify-between mb-6">
            <div>
              <h3 className="text-base font-extrabold text-white">{t("dashboard.salesOverview")}</h3>
              <p className="text-xs text-slate-400">Performance trajectory for 2026</p>
            </div>
          </div>

          {/* SVG Bar Chart */}
          <div className="h-64 flex items-end justify-between gap-3 pt-6 border-b border-slate-800 pb-2">
            {revenueChartData.map((item) => {
              const maxVal = 30000;
              const revHeight = (item.revenue / maxVal) * 100;
              const expHeight = (item.expenses / maxVal) * 100;
              return (
                <div key={item.month} className="flex-1 flex flex-col items-center gap-2 group">
                  <div className="w-full flex items-end justify-center gap-1.5 h-48">
                    <div
                      style={{ height: `${revHeight}%` }}
                      className="w-full max-w-[16px] bg-gradient-to-t from-cyan-600 to-cyan-400 rounded-t-md group-hover:brightness-125 transition-all"
                      title={`Revenue: ${formatCurrency(item.revenue)}`}
                    ></div>
                    <div
                      style={{ height: `${expHeight}%` }}
                      className="w-full max-w-[16px] bg-gradient-to-t from-purple-600 to-purple-400 rounded-t-md opacity-70 group-hover:opacity-100 transition-all"
                      title={`Expenses: ${formatCurrency(item.expenses)}`}
                    ></div>
                  </div>
                  <span className="text-[11px] font-bold text-slate-400 group-hover:text-white transition-colors">
                    {item.month}
                  </span>
                </div>
              );
            })}
          </div>
        </div>

        {/* Activity Feed Column */}
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl flex flex-col justify-between">
          <div>
            <div className="flex items-center justify-between mb-4">
              <h3 className="text-base font-extrabold text-white">{t("navigation.menu.tasks")}</h3>
              <a href="/tasks" className="text-xs font-bold text-cyan-400 hover:underline">{t("common.view")}</a>
            </div>
            <div className="space-y-3">
              {tasks.map((task: any) => (
                <div key={task.id} className="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
                  <div className="space-y-1">
                    <p className="text-xs font-bold text-slate-200">{task.name || task.title}</p>
                    <div className="flex items-center gap-2 text-[10px] text-slate-400">
                      <Clock className="h-3 w-3 text-slate-500" />
                      <span>Due: {task.startdate || task.dueDate || "2026-08-01"}</span>
                    </div>
                  </div>
                  <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full border ${
                    task.status === "Done" || task.status === 2
                      ? "bg-emerald-500/10 text-emerald-400 border-emerald-500/20"
                      : "bg-cyan-500/10 text-cyan-400 border-cyan-500/20"
                  }`}>
                    {task.status === 2 ? "Done" : "In Progress"}
                  </span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
