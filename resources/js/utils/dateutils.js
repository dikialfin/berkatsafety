
export const parseDateToTimeString = (dateString) => {
    const d = new Date(Date.parse(dateString));
    return `${d.getFullYear()}-${plusZero(d.getMonth()+1)}-${plusZero(d.getDate())} ${plusZero(d.getHours())}:${plusZero(d.getMinutes())}`;
  
  }
  
  export function plusZero(i) {
    return i < 10 ? '0' + i : i
  }