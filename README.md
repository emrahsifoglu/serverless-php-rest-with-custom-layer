aws lambda invoke \
    --function-name php-serverless-dev-goodbye \
    --invocation-type RequestResponse \
    --cli-binary-format raw-in-base64-out \
    --payload '{"queryStringParameters": {"name": "world!"}}' \
    output.json | jq
    
aws lambda invoke \
    --function-name php-serverless-dev-hello \  
    --invocation-type RequestResponse \
    --cli-binary-format raw-in-base64-out \
    --payload '{"queryStringParameters": {"name": "world!"}}' \
    output.json | jq
