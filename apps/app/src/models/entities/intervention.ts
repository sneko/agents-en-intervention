import z from 'zod';

import { UserSchema } from '@aei/app/src/models/entities/user';

export const InterventionSchema = z
  .object({
    id: z.string().uuid(),
    userId: z.string().uuid(),
    firstname: UserSchema.shape.firstname,
    lastname: UserSchema.shape.lastname,
    email: UserSchema.shape.email,
    profilePicture: UserSchema.shape.profilePicture,
    canEverything: z.boolean(),
    createdAt: z.date(),
    updatedAt: z.date(),
    deletedAt: z.date().nullable(),
  })
  .strict();
export type InterventionSchemaType = z.infer<typeof InterventionSchema>;
