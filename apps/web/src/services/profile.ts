import type { Profile } from '../lib/types';
import { http } from './http';

export const profileService = {
  async get(): Promise<Profile> {
    const res = await http.get<Profile>('/profile');
    return res.data;
  },
};

