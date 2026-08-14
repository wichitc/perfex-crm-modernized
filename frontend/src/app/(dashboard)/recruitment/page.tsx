"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { UserPlus, Star, Plus } from "lucide-react";

export default function RecruitmentPage() {
  const { t } = useTranslation();

  const { data: recData } = useQuery({
    queryKey: ["recruitment"],
    queryFn: async () => {
      const response = await apiClient.get("/recruitment/overview");
      return response.data;
    },
  });

  const rec = recData || {
    jobOpenings: [
      { id: 1, title: "Senior Fullstack Next.js Developer", department: "Engineering", applicants: 18, status: "Active" },
      { id: 2, title: "Enterprise Account Executive", department: "Sales", applicants: 12, status: "Active" },
    ],
    candidates: [
      { id: 101, name: "Chaiwat Saelim", position: "Senior Fullstack Next.js Developer", stage: "Interview", rating: 4.8 },
      { id: 102, name: "Pornpimol Wong", position: "Enterprise Account Executive", stage: "Offered", rating: 4.9 },
    ]
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <UserPlus className="h-6 w-6 text-indigo-400" />
            {t("recruitment.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("recruitment.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("recruitment.addNewJob")}
        </button>
      </div>

      {/* Job Openings */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
        {rec.jobOpenings.map((job: any) => (
          <div key={job.id} className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg space-y-3">
            <div className="flex items-center justify-between">
              <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">{job.department}</span>
              <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                {job.status}
              </span>
            </div>
            <h3 className="text-base font-extrabold text-white">{job.title}</h3>
            <p className="text-xs text-slate-400">{job.applicants} Applicants Received</p>
          </div>
        ))}
      </div>

      {/* Candidates List */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
        <h3 className="text-base font-extrabold text-white">Active Candidate Candidates</h3>
        <div className="space-y-3">
          {rec.candidates.map((cand: any) => (
            <div key={cand.id} className="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 flex items-center justify-between">
              <div>
                <p className="text-xs font-bold text-white">{cand.name}</p>
                <p className="text-[10px] text-slate-400 mt-0.5">{cand.position}</p>
              </div>
              <div className="flex items-center gap-4">
                <span className="flex items-center gap-1 text-xs font-bold text-amber-400">
                  <Star className="h-3.5 w-3.5 fill-amber-400" /> {cand.rating}
                </span>
                <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                  {cand.stage}
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
