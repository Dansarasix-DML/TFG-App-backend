import { CCarousel, CCarouselItem, CImage, CCarouselCaption } from '@coreui/react';
import './carousel.css';

export default function Carousel() {
    return (
        <div className='divFlex2'>
            <CCarousel controls indicators className='carouselDiv'>
                <CCarouselItem>
                    <CImage className="d-block w-100" src="https://picsum.photos/id/1/900/400" alt="slide 1" />
                    <CCarouselCaption className="d-none d-md-block">
                        <h5>First slide label</h5>
                        <p>Some representative placeholder content for the first slide.</p>
                    </CCarouselCaption>
                </CCarouselItem>
                <CCarouselItem>
                    <CImage className="d-block w-100" src="https://picsum.photos/id/2/900/400" alt="slide 2" />
                    <CCarouselCaption className="d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Some representative placeholder content for the first slide.</p>
                    </CCarouselCaption>
                </CCarouselItem>
                <CCarouselItem>
                    <CImage className="d-block w-100" src="https://picsum.photos/id/3/900/400" alt="slide 3" />
                    <CCarouselCaption className="d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Some representative placeholder content for the first slide.</p>
                    </CCarouselCaption>
                </CCarouselItem>
            </CCarousel>
        </div>
    )
}