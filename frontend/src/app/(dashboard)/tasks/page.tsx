"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_TASKS } from "@/lib/mock-data";
import { CheckSquare, Plus, Clock, User } from "lucide-react";

export default function TasksPage() {
  const { data: tasks } = useQuery({
    queryKey: ["tasks"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/tasks");
        return response.data;
      } catch {
        return MOCK_TASKS;
      }
    },
    initialData: MOCK_TASKS,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <CheckSquare className="h-6 w-6 text-cyan-400" />
            Tasks & Project Management
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Organize task checklists, priority levels, assignees, and deadlines.
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> Create New Task
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">Task Name</th>
              <th className="p-4">Priority</th>
              <th className="p-4">Assignee</th>
              <th className="p-4">Due Date</th>
              <th className="p-4 text-right">Status</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {tasks.map((t: any) => (
              <tr key={t.id} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-bold text-white">{t.title}</td>
                <td className="p-4">
                  <span className={`px-2 py-0.5 rounded-md text-[10px] font-bold ${
                    t.priority === "High" ? "bg-rose-500/10 text-rose-400 border border-rose-500/20" : "bg-slate-800 text-slate-300"
                  }`}>
                    {t.priority}
                  </span>
                </td>
                <td className="p-4 text-slate-300 flex items-center gap-1.5 mt-2">
                  <User className="h-3.5 w-3.5 text-slate-500" /> {t.assignee}
                </td>
                <td className="p-4 text-slate-400">{t.dueDate}</td>
                <td className="p-4 text-right">
                  <span className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold ${
                    t.status === "Done" ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" : "bg-cyan-500/10 text-cyan-400 border border-cyan-500/20"
                  }`}>
                    {t.status}
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
