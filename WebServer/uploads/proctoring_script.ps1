Invoke-WebRequest -Uri http://24.jubilian.one/ -Method POST -Body ($data|ConvertTo-Json) -ContentType "application/json"