#!/bin/bash

# Test API dengan Authentication Admin
# Usage: ./test_api_with_auth.sh [base_url]

BASE_URL="${1:-https://slitty-production.up.railway.app}"
COOKIE_FILE="cookies.txt"

# Clean up old cookie file
rm -f "$COOKIE_FILE"

echo "=========================================="
echo "Testing API with Admin Authentication"
echo "Base URL: $BASE_URL"
echo "=========================================="
echo ""

# Step 1: Get login page to get CSRF token and captcha
echo "1. Getting login page (for CSRF token and captcha)..."
LOGIN_PAGE=$(curl -s -c "$COOKIE_FILE" "$BASE_URL/login")

# Extract CSRF token (macOS compatible)
CSRF_TOKEN=$(echo "$LOGIN_PAGE" | grep 'name="_token"' | sed -n 's/.*value="\([^"]*\)".*/\1/p' | head -1)

# Extract captcha numbers (macOS compatible)
CAPTCHA_A=$(echo "$LOGIN_PAGE" | grep -oE '[0-9]+' | head -1 || echo "5")
CAPTCHA_B=$(echo "$LOGIN_PAGE" | grep -oE '[0-9]+' | tail -1 || echo "3")

# If captcha not found, use default
if [ -z "$CAPTCHA_A" ] || [ -z "$CAPTCHA_B" ]; then
    CAPTCHA_A=5
    CAPTCHA_B=3
fi

CAPTCHA_ANSWER=$((CAPTCHA_A + CAPTCHA_B))

if [ -z "$CSRF_TOKEN" ]; then
    echo "   ⚠️  Warning: Could not extract CSRF token, trying without it"
fi

echo "   CSRF Token: ${CSRF_TOKEN:0:20}..."
echo "   Captcha: $CAPTCHA_A + $CAPTCHA_B = $CAPTCHA_ANSWER"
echo ""

# Step 2: Login as admin
echo "2. Logging in as admin..."
LOGIN_RESPONSE=$(curl -s -b "$COOKIE_FILE" -c "$COOKIE_FILE" -L \
    -X POST "$BASE_URL/login" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -H "Accept: text/html" \
    -d "email=adminK4@gmail.com" \
    -d "password=admin123" \
    -d "captcha=$CAPTCHA_ANSWER" \
    -d "_token=$CSRF_TOKEN" \
    -w "\n%{http_code}")

HTTP_CODE=$(echo "$LOGIN_RESPONSE" | tail -1)
LOGIN_BODY=$(echo "$LOGIN_RESPONSE" | sed '$d')

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
    echo "   ✅ Login successful (HTTP $HTTP_CODE)"
    
    # Check if redirected to dashboard
    if echo "$LOGIN_BODY" | grep -q "dashboard\|Dashboard" || [ "$HTTP_CODE" = "302" ]; then
        echo "   ✅ Redirected to dashboard"
    fi
else
    echo "   ❌ Login failed (HTTP $HTTP_CODE)"
    echo "   Response: $(echo "$LOGIN_BODY" | head -5)"
    exit 1
fi
echo ""

# Step 3: Test API endpoints with authentication
echo "3. Testing API endpoints with authentication..."
echo ""

# Test 3.1: GET /api/petugas
echo "3.1. GET /api/petugas"
API_RESPONSE=$(curl -s -b "$COOKIE_FILE" \
    -H "Accept: application/json" \
    -w "\n%{http_code}" \
    "$BASE_URL/api/petugas")

HTTP_CODE=$(echo "$API_RESPONSE" | tail -1)
API_BODY=$(echo "$API_RESPONSE" | sed '$d')

if [ "$HTTP_CODE" = "200" ]; then
    echo "   ✅ Success (HTTP $HTTP_CODE)"
    echo "$API_BODY" | jq '.' 2>/dev/null || echo "$API_BODY" | head -10
else
    echo "   ❌ Failed (HTTP $HTTP_CODE)"
    echo "   Response: $API_BODY"
fi
echo ""

# Test 3.2: GET /api/petugas/{id} (get first petugas)
echo "3.2. GET /api/petugas/{id} (first petugas)"
FIRST_ID=$(echo "$API_BODY" | jq -r '.data[0].id' 2>/dev/null || echo "1")
if [ -n "$FIRST_ID" ] && [ "$FIRST_ID" != "null" ]; then
    API_RESPONSE=$(curl -s -b "$COOKIE_FILE" \
        -H "Accept: application/json" \
        -w "\n%{http_code}" \
        "$BASE_URL/api/petugas/$FIRST_ID")
    
    HTTP_CODE=$(echo "$API_RESPONSE" | tail -1)
    API_BODY=$(echo "$API_RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ]; then
        echo "   ✅ Success (HTTP $HTTP_CODE)"
        echo "$API_BODY" | jq '.' 2>/dev/null || echo "$API_BODY" | head -10
    else
        echo "   ❌ Failed (HTTP $HTTP_CODE)"
        echo "   Response: $API_BODY"
    fi
else
    echo "   ⚠️  No petugas found to test"
fi
echo ""

# Test 3.3: POST /api/petugas (with validation test)
echo "3.3. POST /api/petugas (test validation - invalid data)"
API_RESPONSE=$(curl -s -b "$COOKIE_FILE" \
    -X POST \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -w "\n%{http_code}" \
    -d '{"username":"test","email":"invalid-email","password":"123"}' \
    "$BASE_URL/api/petugas")

HTTP_CODE=$(echo "$API_RESPONSE" | tail -1)
API_BODY=$(echo "$API_RESPONSE" | sed '$d')

if [ "$HTTP_CODE" = "422" ]; then
    echo "   ✅ Validation working (HTTP 422)"
    echo "$API_BODY" | jq '.' 2>/dev/null || echo "$API_BODY" | head -10
else
    echo "   ⚠️  Unexpected response (HTTP $HTTP_CODE)"
    echo "   Response: $API_BODY"
fi
echo ""

# Test 3.4: POST /api/petugas (with valid data - but might fail if email exists)
echo "3.4. POST /api/petugas (test with valid data)"
TIMESTAMP=$(date +%s)
API_RESPONSE=$(curl -s -b "$COOKIE_FILE" \
    -X POST \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -w "\n%{http_code}" \
    -d "{\"username\":\"testuser$TIMESTAMP\",\"email\":\"test$TIMESTAMP@test.com\",\"password\":\"test123456\"}" \
    "$BASE_URL/api/petugas")

HTTP_CODE=$(echo "$API_RESPONSE" | tail -1)
API_BODY=$(echo "$API_RESPONSE" | sed '$d')

if [ "$HTTP_CODE" = "201" ]; then
    echo "   ✅ Success (HTTP 201)"
    NEW_ID=$(echo "$API_BODY" | jq -r '.data.id' 2>/dev/null || echo "")
    echo "$API_BODY" | jq '.' 2>/dev/null || echo "$API_BODY" | head -10
    
    # Test DELETE if creation successful
    if [ -n "$NEW_ID" ] && [ "$NEW_ID" != "null" ]; then
        echo ""
        echo "3.5. DELETE /api/petugas/$NEW_ID (cleanup)"
        DELETE_RESPONSE=$(curl -s -b "$COOKIE_FILE" \
            -X DELETE \
            -H "Accept: application/json" \
            -w "\n%{http_code}" \
            "$BASE_URL/api/petugas/$NEW_ID")
        
        DELETE_HTTP_CODE=$(echo "$DELETE_RESPONSE" | tail -1)
        DELETE_BODY=$(echo "$DELETE_RESPONSE" | sed '$d')
        
        if [ "$DELETE_HTTP_CODE" = "200" ]; then
            echo "   ✅ Deleted successfully (HTTP 200)"
        else
            echo "   ⚠️  Delete response (HTTP $DELETE_HTTP_CODE)"
        fi
    fi
elif [ "$HTTP_CODE" = "422" ]; then
    echo "   ⚠️  Validation error (HTTP 422) - might be duplicate email"
    echo "$API_BODY" | jq '.' 2>/dev/null || echo "$API_BODY" | head -10
else
    echo "   ⚠️  Unexpected response (HTTP $HTTP_CODE)"
    echo "   Response: $API_BODY"
fi
echo ""

# Test 3.6: GET /api/galeri
echo "3.6. GET /api/galeri"
API_RESPONSE=$(curl -s -b "$COOKIE_FILE" \
    -H "Accept: application/json" \
    -w "\n%{http_code}" \
    "$BASE_URL/api/galeri")

HTTP_CODE=$(echo "$API_RESPONSE" | tail -1)
API_BODY=$(echo "$API_RESPONSE" | sed '$d')

if [ "$HTTP_CODE" = "200" ]; then
    echo "   ✅ Success (HTTP $HTTP_CODE)"
    COUNT=$(echo "$API_BODY" | jq '.data | length' 2>/dev/null || echo "N/A")
    echo "   Count: $COUNT"
    echo "$API_BODY" | jq '.data[0:2]' 2>/dev/null || echo "$API_BODY" | head -10
else
    echo "   ❌ Failed (HTTP $HTTP_CODE)"
    echo "   Response: $API_BODY"
fi
echo ""

# Cleanup
rm -f "$COOKIE_FILE"

echo "=========================================="
echo "Test Complete!"
echo "=========================================="

