import { Hall } from "../Hall.model";
import { Movie } from "../Movie.model";

export interface ShowTime {
    id: number;
    date: Date | string;
    start_time: string;
    end_time: string;
    created_at: Date | string;
    hall: Hall;
    movie: Movie
}