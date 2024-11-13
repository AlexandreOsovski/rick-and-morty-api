/** @type {import('next').NextConfig} */
const nextConfig = {
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: 'http://localhost:8001/:path*',
      },
    ];
  },
  reactStrictMode: true,
  webpack(config: any) {
    return config;
  },
};

module.exports = nextConfig;
