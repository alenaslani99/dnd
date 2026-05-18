import { ref } from 'vue'

export function useDragScroll() {
    const el = ref<HTMLElement | null>(null)
    let isDown = false
    let startX = 0
    let scrollLeft = 0

    function onMouseDown(e: MouseEvent) {
        if (!el.value) {
            return
        }
        isDown = true
        el.value.classList.add('cursor-grabbing')
        startX = e.pageX - el.value.offsetLeft
        scrollLeft = el.value.scrollLeft
    }

    function onMouseLeave() {
        isDown = false
        el.value?.classList.remove('cursor-grabbing')
    }

    function onMouseUp() {
        isDown = false
        el.value?.classList.remove('cursor-grabbing')
    }

    function onMouseMove(e: MouseEvent) {
        if (!isDown || !el.value) {
            return
        }
        e.preventDefault()
        const x = e.pageX - el.value.offsetLeft
        const walk = (x - startX) * 1.5
        el.value.scrollLeft = scrollLeft - walk
    }

    return {
        el,
        onMouseDown,
        onMouseLeave,
        onMouseUp,
        onMouseMove,
    }
}
