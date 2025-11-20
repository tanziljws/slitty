#!/bin/bash

# Test API Petugas dan User
# Usage: ./test_api_petugas.sh [base_url]

BASE_URL="${1:-https://slitty-production.up.railway.app}"

echo "=========================================="
echo "Testing API Petugas & User"
echo "Base URL: $BASE_URL"
echo "=========================================="
echo ""

# Test 1: Test API tanpa auth (should fail)
echo "1. Testing GET /api/petugas (without auth)..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -H "Accept: application/json" "$BASE_URL/api/petugas")
if [ "$HTTP_CODE" = "401" ] || [ "$HTTP_CODE" = "403" ]; then
    echo "   ✅ Success: API requires authentication (HTTP $HTTP_CODE)"
else
    echo "   ⚠️  Warning: API response (HTTP $HTTP_CODE) - should be 401/403"
fi

# Test 2: Test API User tanpa auth
echo "2. Testing GET /api/galeri (without auth)..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -H "Accept: application/json" "$BASE_URL/api/galeri")
if [ "$HTTP_CODE" = "401" ] || [ "$HTTP_CODE" = "403" ]; then
    echo "   ✅ Success: API requires authentication (HTTP $HTTP_CODE)"
else
    echo "   ⚠️  Warning: API response (HTTP $HTTP_CODE) - should be 401/403"
fi

# Test 3: Test validation - try to create petugas without data
echo "3. Testing POST /api/petugas (without data - should fail validation)..."
# Note: This will fail because no auth, but we can see the response
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" \
    -X POST \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    "$BASE_URL/api/petugas")
echo "   Response: HTTP $HTTP_CODE"

# Test 4: Check database directly
echo ""
echo "4. Checking database validation..."
echo "   (This requires database access)"
echo "   - Petugas table: username, email, password required"
echo "   - Users table: name, email, password required"
echo ""

# Test 5: Check validation rules
echo "5. Validation Rules Summary:"
echo ""
echo "   📋 Petugas (Store):"
echo "      - username: required|string|max:255"
echo "      - email: required|email|unique:petugas"
echo "      - password: required|string|min:6"
echo ""
echo "   📋 Petugas (Update):"
echo "      - username: required|string|max:255"
echo "      - email: required|email|unique:petugas,email,{id}"
echo "      - password: nullable|string|min:6"
echo ""
echo "   📋 User (Register):"
echo "      - name: required|string|max:255"
echo "      - email: required|email|unique:users,email"
echo "      - password: required|string|min:6|confirmed"
echo "      - captcha: required|numeric"
echo ""

echo "=========================================="
echo "Test Complete!"
echo "=========================================="
echo ""
echo "⚠️  Note: API endpoints require authentication"
echo "   To test with auth, you need to:"
echo "   1. Login first to get session/cookie"
echo "   2. Use that session/cookie for API requests"
echo ""

