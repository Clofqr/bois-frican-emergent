from fastapi import FastAPI

app = FastAPI(title="Bois Frican - backend stub")


@app.get("/api/health")
async def health():
    return {"status": "ok", "app": "bois-frican-php", "note": "Site PHP servi par le frontend"}
