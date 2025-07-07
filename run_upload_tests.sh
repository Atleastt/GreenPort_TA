#!/bin/bash

# ==============================================
# UPLOAD TEST RUNNER SCRIPT
# ==============================================
# Gunakan script ini untuk menjalankan test upload dengan mudah

echo "🚀 UPLOAD DOCUMENT TEST RUNNER"
echo "================================"

# Set working directory
cd /Users/leonfarhan/Documents/code/joki/TA-aom/greenport

echo ""
echo "📋 Available Test Options:"
echo "1. Run All Upload Tests"
echo "2. Test File Sizes (2MB, 5MB, 10MB)"
echo "3. Test Performance & Concurrent"
echo "4. Test Validation & Error Handling"
echo "5. Test CRUD Operations"
echo "6. Quick File Size Test"
echo "7. Performance Benchmark"
echo ""

read -p "Choose option (1-7): " choice

case $choice in
    1)
        echo "🧪 Running ALL Upload Tests..."
        php artisan test --filter DocumentUploadTest
        ;;
    2)
        echo "📊 Testing File Sizes: 2MB, 5MB, 10MB..."
        php artisan test --filter "can_upload_2mb_document|can_upload_5mb_document|can_upload_10mb_document"
        ;;
    3)
        echo "⚡ Testing Performance & Concurrent Upload..."
        php artisan test --filter "test_upload_performance_with_different_file_sizes|test_concurrent_upload_handling"
        ;;
    4)
        echo "🛡️ Testing Validation & Error Handling..."
        php artisan test --filter "cannot_upload_50mb_document|validates_file_types|cannot_upload_file_over_10mb"
        ;;
    5)
        echo "🔄 Testing CRUD Operations..."
        php artisan test --filter "can_download_uploaded_document|can_delete_uploaded_document|can_approve_document|can_reject_document"
        ;;
    6)
        echo "🚀 Quick File Size Test (2MB only)..."
        php artisan test --filter "can_upload_2mb_document"
        ;;
    7)
        echo "📈 Performance Benchmark..."
        php artisan test --filter "test_upload_performance_with_different_file_sizes"
        ;;
    *)
        echo "❌ Invalid option. Please choose 1-7."
        exit 1
        ;;
esac

echo ""
echo "✅ Test completed!"
echo "📊 Check the output above for results."
