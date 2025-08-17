from fastapi import FastAPI, Query
from fastapi.responses import JSONResponse
import httpx

app = FastAPI()

API_URL = "https://api.imkonai.uz/v1/chat/completions"

@app.get("/chat")
async def chat(savol: str = Query(..., description="Savol yuboring")):
    if not savol.strip():
        return JSONResponse(
            status_code=400,
            content={"error": "Iltimos, ?savol= parametrini yuboring"}
        )

    data = {
        "chat": savol.strip(),
        "model": "imkonai",
        "temperature": 0.7,
        "max_tokens": 500
    }

    try:
        async with httpx.AsyncClient(timeout=1000.0) as client:
            response = await client.post(
                API_URL,
                json=data,
                headers={"Content-Type": "application/json"}
            )

        if response.status_code >= 400:
            return JSONResponse(
                status_code=500,
                content={"error": "API bilan aloqa bo‘lmadi", "details": response.text}
            )

        return JSONResponse(content=response.json(), status_code=200)

    except Exception as e:
        return JSONResponse(
            status_code=500,
            content={"error": "API chaqirishda xato", "details": str(e)}
        )
