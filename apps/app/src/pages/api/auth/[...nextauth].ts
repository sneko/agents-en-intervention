import type { NextAuthOptions } from 'next-auth';
import NextAuth from 'next-auth';
import CredentialsProvider from 'next-auth/providers/credentials';

import { fetchLoginCheckPost } from '@aei/app/src/client/generated/components';
import { TokenUserSchemaType } from '@aei/app/src/models/entities/user';
import { getBaseUrl } from '@aei/app/src/utils/url';

const baseUrl = getBaseUrl();

// It requires an environment variable always equal to the base URL
process.env.NEXTAUTH_URL = getBaseUrl();

export const nextAuthOptions: NextAuthOptions = {
  debug: process.env.NODE_ENV !== 'production',
  pages: {
    signIn: '/auth/sign-in',
    signOut: '/auth/sign-in',
    error: '/auth/sign-in',
    // verifyRequest: '/auth/sign-in',
    newUser: '/auth/sign-up',
  },
  providers: [
    CredentialsProvider({
      id: 'credentials',
      name: 'Connexion',
      async authorize(credentials: any): Promise<TokenUserSchemaType> {
        // TODO: parse with zod SignInSchema
        if (!credentials.email || !credentials.password) {
          throw new Error('credentials_required');
        }

        const res = await fetchLoginCheckPost({
          body: {
            login: credentials.email,
            password: credentials.password,
          },
        });

        if (res.token) {
          throw new Error(res.token);

          // return Promise.resolve(user);
          // return Promise.resolve(user);
        } else {
          throw new Error('no_credentials_match');
        }
      },
      credentials: {
        email: { label: 'Email', type: 'text' },
        password: { label: 'Password', type: 'password' },
      },
    }),
  ],
  callbacks: {
    async jwt({ token, user }) {
      if (user) {
        return {
          ...token,
          sub: user.id,
          email: user.email,
          given_name: user.firstname,
          family_name: user.lastname,
          picture: user.profilePicture,
        };
      }

      return token;
    },
    async redirect({ url }) {
      const baseUrl = getBaseUrl();

      if (url.startsWith('/')) {
        // Allows relative callback URLs
        return `${baseUrl}${url}`;
      } else if (new URL(url).origin === baseUrl) {
        // Allows callback URLs on the same origin
        return url;
      } else {
        // For security reason the default case is to redirect to home
        return baseUrl;
      }
    },
  },
};

export default NextAuth(nextAuthOptions);
