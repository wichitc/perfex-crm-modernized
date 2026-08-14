"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { CheckSquare, Plus, User } from "lucide-react";

export default function TasksPage() {
  const { t, formatDate } = useTranslation();

  const { data: tasksData = [] } = useQuery({
    queryKey: ["tasks"],
    queryFn: async () => {
      const response = await apiClient.get("/tasks");
      return response.data;
    },
  });

  const defaultTasks = [
    { id: 1, name: "Deploy Next.js 16 Multi-Theme Switcher", priority: "High", status: "In Progress", startdate: "2026-07-30", assignee: "Frontend Agent" },
    { id: 2, name: "Verify Client & Lead Management Views", priority: "Medium", status: "Done", startdate: "2026-07-28", assignee: "QA Lead" },
  ];

  const tasks = tasksData.length > 0 ? tasksData : defaultTasks;

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <CheckSquare className="h-6 w-6 text-cyan-400" />
            {t("task.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("task.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("task.addNewTask")}
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">{t("task.taskName")}</th>
              <th className="p-4">Priority</th>
              <th className="p-4">{t("task.assignee")}</th>
              <th className="p-4">{t("task.dueDate")}</th>
              <th className="p-4 text-right">{t("common.status")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {tasks.map((tItem: any) => (
              <tr key={tItem.id} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-bold text-white">{tItem.name || tItem.title}</td>
                <td className="p-4">
                  <span className={`px-2 py-0.5 rounded-md text-[10px] font-bold ${
                    tItem.priority === "High" || tItem.priority === 3 ? "bg-rose-500/10 text-rose-400 border border-rose-500/20" : "bg-slate-800 text-slate-300"
                  }`}>
                    {tItem.priority === "High" || tItem.priority === 3 ? t("task.priority.high") : t("task.priority.medium")}
                  </span>
                </td>
                <td className="p-4 text-slate-300 flex items-center gap-1.5 mt-2">
                  <User className="h-3.5 w-3.5 text-slate-500" /> {tItem.assignee || "Lead Staff"}
                </td>
                <td className="p-4 text-slate-400">{formatDate(tItem.startdate || tItem.dueDate || "2026-07-30")}</td>
                <td className="p-4 text-right">
                  <span className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold ${
                    tItem.status === "Done" || tItem.status === 2 ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" : "bg-cyan-500/10 text-cyan-400 border border-cyan-500/20"
                  }`}>
                    {tItem.status === 2 ? "Done" : "In Progress"}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
