import { ImageResponse } from 'next/og'
 
// Image metadata
export const size = {
  width: 720,
  height: 720,
}
export const contentType = 'image/png'
 
// Image generation
export default function Icon() {
  return new ImageResponse(
    (
      <div
        style={{
          fontSize: 300,
          background: 'black',
          width: '100%',
          height: '100%',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          color: 'white',
        }}
      >
        E
      </div>
    ),
    {
      ...size,
    }
  )
}
