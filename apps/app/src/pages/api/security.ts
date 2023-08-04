import addDays from 'date-fns/addDays';
import { NextApiRequest, NextApiResponse } from 'next';

export function handler(req: NextApiRequest, res: NextApiResponse) {
  const expirationDate = addDays(new Date(), 7);
  const content = `
Contact: https://github.com/sneko/agents-en-intervention/issues
Expires: ${expirationDate.toISOString()}
Preferred-Languages: fr, en
`;

  res.send(content.trim());
}

export default handler;
