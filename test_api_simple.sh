#!/bin/bash

# Test API dengan Authentication Admin (Simplified)
# Usage: ./test_api_simple.sh [base_url]

BASE_URL="${1:-https://slitty-production.up.railway.app}"
COOKIE_FILE="cookies.txt"

# Clean up old cookie file
rm -f "$COOKIE_FILE"

echo "=========================================="
echo "Testing API with Admin Authentication"
echo "Base URL: $BASE_URL"
echo "=========================================="
echo ""

# Step 1: Get login page and extract CSRF token
echo "1. Getting login page (for CSRF token and captcha)..."
LOGIN_PAGE=$(curl -s -c "$COOKIE_FILE" -L "$BASE_URL/login")

# Extract CSRF token (better method for macOS)
CSRF_TOKEN=$(echo "$LOGIN_PAGE" | sed -n 's/.*name="_token"[^>]*value="\([^"]*\)".*/\1/p' | head -1)

# Extract captcha numbers - look for pattern like "8 + 4"
CAPTCHA_LINE=$(echo "$LOGIN_PAGE" | grep -E '[0-9]+\s*\+\s*[0-9]+' | head -1)
if [ -n "$CAPTCHA_LINE" ]; then
    CAPTCHA_A=$(echo "$CAPTCHA_LINE" | grep -oE '[0-9]+' | head -1)
    CAPTCHA_B=$(echo "$CAPTCHA_LINE" | grep -oE '[0-9]+' | tail -1)
else
    # Fallback: extract any two numbers
    NUMBERS=($(echo "$LOGIN_PAGE" | grep -oE '[0-9]+' | head -2))
    CAPTCHA_A=${NUMBERS[0]:-5}
    CAPTCHA_B=${NUMBERS[1]:-3}
fi

CAPTCHA_ANSWER=$((CAPTCHA_A + CAPTCHA_B))

echo "   CSRF Token: ${CSRF_TOKEN:0:30}..."
echo "   Captcha: $CAPTCHA_A + $CAPTCHA_B = $CAPTCHA_ANSWER"
echo ""

if [ -z "$CSRF_TOKEN" ]; then
    echo "   ❌ Error: Could not extract CSRF token"
    exit 1
fi

# Step 2: Login as admin
echo "2. Logging in as admin (adminK4@gmail.com)..."
LOGIN_RESPONSE=$(curl -s -b "$COOKIE_FILE" -c "$COOKIE_FILE" -L \
    -X POST "$BASE_URL/login" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -H "Accept: text/html" \
    -H "Referer: $BASE_URL/login" \
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
    if echo "$LOGIN_BODY" | grep -qi "dashboard\|Dashboard" || [ "$HTTP_CODE" = "302" ]; then
        echo "   ✅ Redirected to dashboard"
    fi
else
    echo "   ❌ Login failed (HTTP $HTTP_CODE)"
    if [ "$HTTP_CODE" = "419" ]; then
        echo "   ⚠️  CSRF token mismatch - trying alternative method..."
        # Try with fresh session
        rm -f "$COOKIE_FILE"
        LOGIN_PAGE=$(curl -s -c "$COOKIE_FILE" -L "$BASE_URL/login")
        CSRF_TOKEN=$(echo "$LOGIN_PAGE" | sed -n 's/.*name="_token"[^>]*value="\([^"]*\)".*/\1/p' | head -1)
        echo "   Retrying with new CSRF token: ${CSRF_TOKEN:0:30}..."
        
        LOGIN_RESPONSE=$(curl -s -b "$COOKIE_FILE" -c "$COOKIE_FILE" -L \
            -X POST "$BASE_URL/login" \
            -H "Content-Type: application/x-www-form-urlencoded" \
            -H "Accept: text/html" \
            -H "Referer: $BASE_URL/login" \
            -d "email=adminK4@gmail.com" \
            -d "password=admin123" \
            -d "captcha=$CAPTCHA_ANSWER" \
            -d "_token=$CSRF_TOKEN" \
            -w "\n%{http_code}")
        
        HTTP_CODE=$(echo "$LOGIN_RESPONSE" | tail -1)
        if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
            echo "   ✅ Login successful on retry (HTTP $HTTP_CODE)"
        else
            echo "   ❌ Login still failed (HTTP $HTTP_CODE)"
            echo "   Response preview: $(echo "$LOGIN_RESPONSE" | head -3)"
            exit 1
        fi
    else
        echo "   Response preview: $(echo "$LOGIN_BODY" | head -5)"
        exit 1
    fi
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
    if command -v jq &> /dev/null; then
        echo "$API_BODY" | jq '.' | head -20
        COUNT=$(echo "$API_BODY" | jq '.data | length' 2>/dev/null || echo "N/A")
        echo "   Total petugas: $COUNT"
    else
        echo "$API_BODY" | head -20
    fi
else
    echo "   ❌ Failed (HTTP $HTTP_CODE)"
    echo "   Response: $API_BODY"
fi
echo ""

# Test 3.2: GET /api/petugas/{id}
echo "3.2. GET /api/petugas/{id} (first petugas)"
if command -v jq &> /dev/null && [ "$HTTP_CODE" = "200" ]; then
    FIRST_ID=$(echo "$API_BODY" | jq -r '.data[0].id' 2>/dev/null || echo "1")
    if [ -n "$FIRST_ID" ] && [ "$FIRST_ID" != "null" ] && [ "$FIRST_ID" != "" ]; then
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
else
    echo "   ⚠️  Skipping (jq not available or previous request failed)"
fi
echo ""

# Test 3.3: POST /api/petugas (validation test)
echo "3.3. POST /api/petugas (test validation - invalid email)"
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
    if command -v jq &> /dev/null; then
        echo "$API_BODY" | jq '.'
    else
        echo "$API_BODY"
    fi
else
    echo "   ⚠️  Unexpected response (HTTP $HTTP_CODE)"
    echo "$API_BODY" | head -10
fi
echo ""

# Test 3.4: GET /api/galeri
echo "3.4. GET /api/galeri"
API_RESPONSE=$(curl -s -b "$COOKIE_FILE" \
    -H "Accept: application/json" \
    -w "\n%{http_code}" \
    "$BASE_URL/api/galeri")

HTTP_CODE=$(echo "$API_RESPONSE" | tail -1)
API_BODY=$(echo "$API_RESPONSE" | sed '$d')

if [ "$HTTP_CODE" = "200" ]; then
    echo "   ✅ Success (HTTP $HTTP_CODE)"
    if command -v jq &> /dev/null; then
        COUNT=$(echo "$API_BODY" | jq '.data | length' 2>/dev/null || echo "N/A")
        echo "   Count: $COUNT"
        echo "$API_BODY" | jq '.data[0:2]' 2>/dev/null || echo "$API_BODY" | head -10
    else
        echo "$API_BODY" | head -10
    fi
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

