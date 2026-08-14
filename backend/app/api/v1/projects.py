from typing import List, Optional
from datetime import datetime
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.project import Project, Task
from app.domain.models.client import Client
from app.domain.models.staff import Staff
from app.application.schemas.crm import ProjectCreate, ProjectUpdate, ProjectResponse, TaskCreate, TaskResponse
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/projects", tags=["Projects"])

@router.get("/", response_model=List[ProjectResponse])
async def get_projects(
    offset: int = 0,
    limit: int = 100,
    client_id: Optional[int] = None,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    query = select(Project)
    if client_id is not None:
        query = query.where(Project.clientid == client_id)
        
    result = await db.execute(query.offset(offset).limit(limit))
    return result.scalars().all()

@router.post("/", response_model=ProjectResponse, status_code=status.HTTP_201_CREATED)
async def create_project(
    project_data: ProjectCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    # Verify client exists
    client_check = await db.execute(select(Client).where(Client.userid == project_data.clientid))
    if not client_check.scalar_one_or_none():
        raise HTTPException(status_code=404, detail="Client not found")
        
    db_project = Project(
        addedfrom=current_user.staffid,
        **project_data.model_dump()
    )
    db.add(db_project)
    await db.commit()
    await db.refresh(db_project)
    return db_project

@router.get("/{project_id}", response_model=ProjectResponse)
async def get_project(
    project_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Project).where(Project.id == project_id))
    project = result.scalar_one_or_none()
    if not project:
        raise HTTPException(status_code=404, detail="Project not found")
    return project

@router.put("/{project_id}", response_model=ProjectResponse)
async def update_project(
    project_id: int,
    project_data: ProjectUpdate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Project).where(Project.id == project_id))
    project = result.scalar_one_or_none()
    if not project:
        raise HTTPException(status_code=404, detail="Project not found")
        
    for key, value in project_data.model_dump(exclude_unset=True).items():
        setattr(project, key, value)
        
    if "status" in project_data.model_dump(exclude_unset=True) and project_data.status == 5: # Status 5 = complete
        project.date_finished = datetime.utcnow()
        
    await db.commit()
    await db.refresh(project)
    return project

@router.delete("/{project_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_project(
    project_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Project).where(Project.id == project_id))
    project = result.scalar_one_or_none()
    if not project:
        raise HTTPException(status_code=404, detail="Project not found")
        
    await db.delete(project)
    await db.commit()
    return None

# --- TASK SUB-ENDPOINTS ---
@router.get("/{project_id}/tasks", response_model=List[TaskResponse])
async def get_project_tasks(
    project_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    # Verify project exists
    result = await db.execute(select(Project).where(Project.id == project_id))
    if not result.scalar_one_or_none():
        raise HTTPException(status_code=404, detail="Project not found")
        
    tasks_result = await db.execute(
        select(Task).where(Task.rel_id == project_id, Task.rel_type == "project")
    )
    return tasks_result.scalars().all()

@router.post("/{project_id}/tasks", response_model=TaskResponse, status_code=status.HTTP_201_CREATED)
async def create_project_task(
    project_id: int,
    task_data: TaskCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Project).where(Project.id == project_id))
    if not result.scalar_one_or_none():
        raise HTTPException(status_code=404, detail="Project not found")
        
    db_task = Task(
        rel_id=project_id,
        rel_type="project",
        addedfrom=current_user.staffid,
        **task_data.model_dump(exclude={"rel_id", "rel_type"})
    )
    db.add(db_task)
    await db.commit()
    await db.refresh(db_task)
    return db_task
