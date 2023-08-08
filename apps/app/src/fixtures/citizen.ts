import { addresses } from '@aei/app/src/fixtures/address';
import { phones } from '@aei/app/src/fixtures/phone';
import { CitizenGenderIdentitySchema, CitizenSchema, CitizenSchemaType } from '@aei/app/src/models/entities/citizen';

export const citizens: CitizenSchemaType[] = [
  CitizenSchema.parse({
    id: 'b79cb3ba-745e-5d9a-8903-4a02327a7e01',
    email: 'valentin_rousseau@gmail.com',
    firstname: 'Agathon',
    lastname: 'Remy',
    genderIdentity: CitizenGenderIdentitySchema.Values.FEMALE,
    address: addresses[0],
    phone: phones[0],
    createdAt: new Date('December 17, 2022 03:24:00 UTC'),
    updatedAt: new Date('December 19, 2022 04:33:00 UTC'),
    deletedAt: null,
  }),
  CitizenSchema.parse({
    id: 'b79cb3ba-745e-5d9a-8903-4a02327a7e02',
    email: 'guilhemine.noel@hotmail.fr',
    firstname: 'Amaliane',
    lastname: 'Baron',
    genderIdentity: CitizenGenderIdentitySchema.Values.NON_BINARY,
    address: addresses[1],
    phone: phones[1],
    createdAt: new Date('December 17, 2022 03:24:00 UTC'),
    updatedAt: new Date('December 19, 2022 04:33:00 UTC'),
    deletedAt: null,
  }),
  CitizenSchema.parse({
    id: 'b79cb3ba-745e-5d9a-8903-4a02327a7e03',
    email: 'pascale.leclerc@yahoo.fr',
    firstname: 'Pénélope',
    lastname: 'Rolland',
    genderIdentity: null,
    address: addresses[2],
    phone: phones[2],
    createdAt: new Date('December 17, 2022 03:24:00 UTC'),
    updatedAt: new Date('December 19, 2022 04:33:00 UTC'),
    deletedAt: null,
  }),
];
