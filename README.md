# PHP CloudFront S3 Signed URL Example

1. Create your S3 bucket
2. Create CloudFront distribution on S3 bucket
3. Create CloudFront key pairs (only root account can do this) - http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/private-content-trusted-signers.html#private-content-creating-cloudfront-key-pairs-procedure
4. Add trusted signers to distribution - http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/private-content-trusted-signers.html#private-content-adding-trusted-signers-console
5. Set your private key, cloudfront key pair ID, and BaseURL as Heroku config variables
```
$file = Get-Content C:\temp\MY_PRIVATE_KEY.pem
heroku config:add CLOUDFRONT_KEY="$($file)"
heroku config:add CLOUDFRONT_KEY_PAIRID='MY_CLOUDFRONT_KEY_PAIR_ID'
heroku config:add CLOUDFRONT_BASE_URL='MY_CLOUDFRONT_URL.cloudfront.net'
```
