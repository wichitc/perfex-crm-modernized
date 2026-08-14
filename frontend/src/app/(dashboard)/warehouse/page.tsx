"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { Package, AlertTriangle, Plus, MapPin } from "lucide-react";

export default function WarehousePage() {
  const { t, formatCurrency } = useTranslation();

  const { data: itemsData = [] } = useQuery({
    queryKey: ["warehouse"],
    queryFn: async () => {
      const response = await apiClient.get("/warehouse/items");
      return response.data;
    },
  });

  const defaultItems = [
    { id: "SKU-001", name: "Barcode Scanner Handheld 2D", location: "Bangkok Central Hub", category: "Hardware", stock: 145, minStock: 20, unitPrice: 3500 },
    { id: "SKU-002", name: "Thermal Receipt Printer 80mm", location: "Nonthaburi Depot", category: "Hardware", stock: 82, minStock: 15, unitPrice: 4200 },
  ];

  const items = itemsData.length > 0 ? itemsData : defaultItems;

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Package className="h-6 w-6 text-sky-400" />
            {t("inventory.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("inventory.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("inventory.addNewItem")}
        </button>
      </div>

      {/* Stock Items Table */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">{t("inventory.sku")}</th>
              <th className="p-4">{t("inventory.itemName")}</th>
              <th className="p-4">{t("inventory.warehouse")}</th>
              <th className="p-4">Category</th>
              <th className="p-4">{t("inventory.quantity")}</th>
              <th className="p-4">{t("inventory.unitPrice")}</th>
              <th className="p-4 text-right">{t("common.status")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {items.map((item: any) => (
              <tr key={item.id} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-mono font-bold text-sky-400">{item.id}</td>
                <td className="p-4 font-bold text-white">{item.name}</td>
                <td className="p-4 text-slate-400 flex items-center gap-1.5 mt-2">
                  <MapPin className="h-3.5 w-3.5 text-slate-500" /> {item.location || "Central Hub"}
                </td>
                <td className="p-4 text-slate-400">{item.category || "Hardware"}</td>
                <td className="p-4 font-bold text-white">{item.stock || item.quantity || 100} Units</td>
                <td className="p-4 font-bold text-sky-400">{formatCurrency(item.unitPrice || item.rate || 3500)}</td>
                <td className="p-4 text-right">
                  {(item.stock || 100) <= (item.minStock || 20) ? (
                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                      <AlertTriangle className="h-3 w-3" /> Low Stock
                    </span>
                  ) : (
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                      In Stock
                    </span>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
