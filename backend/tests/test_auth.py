import pytest
from httpx import AsyncClient, ASGITransport
from sqlalchemy.ext.asyncio import AsyncSession
from app.domain.models.staff import Staff
from app.core.security import get_password_hash
from app.main import app

@pytest.mark.asyncio
async def test_auth_flow(db_session: AsyncSession):
    # 1. Seed test user
    hashed_password = get_password_hash("testpassword123")
    test_user = Staff(
        email="test_user@crm.com",
        firstname="John",
        lastname="Doe",
        password=hashed_password,
        admin=1,
        active=1
    )
    db_session.add(test_user)
    await db_session.commit()

    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        # 2. Test Login Success
        login_response = await ac.post("/api/v1/auth/login", json={
            "email": "test_user@crm.com",
            "password": "testpassword123"
        })
        assert login_response.status_code == 200
        data = login_response.json()
        assert "access_token" in data
        assert data["token_type"] == "bearer"
        
        access_token = data["access_token"]
        headers = {"Authorization": f"Bearer {access_token}"}

        # 3. Test Login Failure (wrong pass)
        login_fail = await ac.post("/api/v1/auth/login", json={
            "email": "test_user@crm.com",
            "password": "wrongpassword"
        })
        assert login_fail.status_code == 401

        # 4. Test Get Me success
        me_response = await ac.get("/api/v1/auth/me", headers=headers)
        assert me_response.status_code == 200
        me_data = me_response.json()
        assert me_data["email"] == "test_user@crm.com"

        # 5. Test Get Me failure (no token)
        me_fail = await ac.get("/api/v1/auth/me")
        assert me_fail.status_code == 401
