"use client";

import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { Users, Mail, Phone, Globe, Calendar, Plus, Search, X } from "lucide-react";

export default function ClientsPage() {
  const { t, formatDate } = useTranslation();
  const [searchTerm, setSearchTerm] = useState("");
  const [isModalOpen, setIsModalOpen] = useState(false);

  const { data: clientsData = [] } = useQuery({
    queryKey: ["clients"],
    queryFn: async () => {
      const response = await apiClient.get("/clients");
      return response.data;
    },
  });

  const clients = clientsData.length > 0 ? clientsData : [
    {
      userid: 101,
      company: "Acme Technology Solutions",
      vat: "TH0994827101",
      phonenumber: "+66 2 123 4567",
      city: "Bangkok",
      state: "Bangkok",
      website: "https://acmetechnology.co.th",
      datecreated: "2026-01-15T08:30:00Z",
      active: 1,
      contacts: [
        { id: 1, firstname: "Somchai", lastname: "Jaidee", email: "somchai@acme.co.th", phonenumber: "+66 81 234 5678", title: "IT Director", is_primary: 1 }
      ]
    }
  ];

  const filteredClients = clients.filter(
    (c: any) =>
      c.company.toLowerCase().includes(searchTerm.toLowerCase()) ||
      c.city?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Users className="h-6 w-6 text-cyan-400" />
            {t("customer.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("customer.subtitle")}
          </p>
        </div>
        <button
          onClick={() => setIsModalOpen(true)}
          className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/25 transition-all cursor-pointer"
        >
          <Plus className="h-4 w-4" /> {t("customer.addNew")}
        </button>
      </div>

      {/* Filter bar */}
      <div className="flex items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div className="relative flex-1">
          <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" />
          <input
            type="text"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            placeholder={t("customer.searchPlaceholder")}
            className="w-full bg-slate-950/60 border border-slate-800 text-xs text-slate-200 placeholder:text-slate-500 rounded-xl py-2.5 pl-10 pr-4 focus:outline-none focus:border-cyan-500/60 transition-all"
          />
        </div>
      </div>

      {/* Grid View */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {filteredClients.map((client: any) => (
          <div
            key={client.userid}
            className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl hover:border-slate-700 transition-all duration-300 flex flex-col justify-between"
          >
            <div>
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-lg font-extrabold text-white">{client.company}</h3>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                  {t("customer.active")}
                </span>
              </div>

              <div className="space-y-2 text-xs text-slate-400 border-b border-slate-800/80 pb-4 mb-4">
                {client.phonenumber && (
                  <div className="flex items-center gap-2">
                    <Phone className="h-3.5 w-3.5 text-slate-500" />
                    <span>{client.phonenumber}</span>
                  </div>
                )}
                {client.website && (
                  <div className="flex items-center gap-2">
                    <Globe className="h-3.5 w-3.5 text-slate-500" />
                    <a href={client.website} target="_blank" rel="noreferrer" className="text-cyan-400 hover:underline">
                      {client.website}
                    </a>
                  </div>
                )}
                <div className="flex items-center gap-2">
                  <Calendar className="h-3.5 w-3.5 text-slate-500" />
                  <span>{t("common.date")}: {formatDate(client.datecreated)}</span>
                </div>
              </div>

              {/* Primary Contacts */}
              <div>
                <h4 className="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">
                  {t("customer.primaryContact")}
                </h4>
                <div className="space-y-2">
                  {(client.contacts || []).map((contact: any) => (
                    <div
                      key={contact.id}
                      className="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3 flex items-center justify-between"
                    >
                      <div>
                        <p className="text-xs font-bold text-slate-200">
                          {contact.firstname} {contact.lastname}
                        </p>
                        <p className="text-[10px] text-slate-400">{contact.title || "Contact"}</p>
                      </div>
                      <div className="flex items-center gap-1.5 text-cyan-400 text-[11px] font-semibold">
                        <Mail className="h-3.5 w-3.5" />
                        <span>{contact.email}</span>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Modal Add Client */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 animate-fadeIn">
          <div className="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-5">
            <div className="flex items-center justify-between border-b border-slate-800 pb-3">
              <h3 className="text-base font-extrabold text-white">{t("customer.addNew")}</h3>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-white">
                <X className="h-5 w-5" />
              </button>
            </div>
            <div className="space-y-4 text-xs">
              <div>
                <label className="text-slate-300 font-semibold block mb-1">{t("customer.company")}</label>
                <input type="text" placeholder="e.g. Siam Tech Co., Ltd." className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white" />
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-slate-300 font-semibold block mb-1">Tax ID / VAT</label>
                  <input type="text" placeholder="TH01055..." className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white" />
                </div>
                <div>
                  <label className="text-slate-300 font-semibold block mb-1">{t("customer.phone")}</label>
                  <input type="text" placeholder="+66 2 ..." className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white" />
                </div>
              </div>
            </div>
            <div className="flex justify-end gap-3 pt-3">
              <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">
                {t("common.cancel")}
              </button>
              <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 rounded-xl bg-cyan-500 text-slate-950 text-xs font-bold">
                {t("common.save")}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
