FROM node:20-slim AS deps

RUN apt-get update && apt-get install -y python3 make g++ && rm -rf /var/lib/apt/lists/*
WORKDIR /usr/src/app
COPY package.json package-lock.json ./
RUN npm ci

FROM node:20-slim AS builder

WORKDIR /usr/src/app
ARG NEXT_PUBLIC_API_URL
ARG NEXT_PUBLIC_SITE_TITLE
ARG NEXT_PUBLIC_AUTH_PREFIX
ENV NEXT_PUBLIC_API_URL=$NEXT_PUBLIC_API_URL
ENV NEXT_PUBLIC_SITE_TITLE=$NEXT_PUBLIC_SITE_TITLE
ENV NEXT_PUBLIC_AUTH_PREFIX=$NEXT_PUBLIC_AUTH_PREFIX
COPY --from=deps /usr/src/app/node_modules ./node_modules
COPY . .
RUN npm run build

FROM node:20-slim AS runner

WORKDIR /usr/src/app
ENV NODE_ENV=production

COPY --from=deps /usr/src/app/node_modules ./node_modules
COPY package.json package-lock.json ./
RUN npm prune --omit=dev

COPY --from=builder /usr/src/app/.next ./.next
COPY --from=builder /usr/src/app/public ./public

EXPOSE 3000
CMD ["npm", "run", "start"]
