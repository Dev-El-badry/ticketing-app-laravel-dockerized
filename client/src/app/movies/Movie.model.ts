export interface Movie {
  id: number;
  movie_name: string;
  movie_description: string;
  movie_duration: string;
  release_date: string;
  created_at: Date | string;
}