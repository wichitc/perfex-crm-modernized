import pytest
from httpx import AsyncClient, ASGITransport
from sqlalchemy.ext.asyncio import AsyncSession
from app.domain.models.recruitment import RecruitmentCampaign, RecruitmentCandidate
from app.domain.models.okr import OKR, OKRKeyResult
from app.main import app

@pytest.mark.asyncio
async def test_hrm_and_okrs_flow(db_session: AsyncSession, auth_headers: dict):
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        # 1. Verify Recruitment Overview
        rec_res = await ac.get("/api/v1/recruitment/overview", headers=auth_headers)
        assert rec_res.status_code == 200
        rec_data = rec_res.json()
        assert "jobOpenings" in rec_data
        assert "candidates" in rec_data

        # 2. Check OKRs list
        okrs_res = await ac.get("/api/v1/okrs/", headers=auth_headers)
        assert okrs_res.status_code == 200
        okrs_list = okrs_res.json()
        assert len(okrs_list) >= 1
