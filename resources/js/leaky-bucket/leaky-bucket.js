const elements = {
    room: document.getElementById('room'),
    roomContainer: document.getElementById('room-container'),
    autoToggleButton: document.getElementById('auto-toggle'),
    frequencySlider: document.getElementById('request-frequency'),
    frequencyValue: document.getElementById('frequency-value'),
    requests: document.getElementById('requests'),
    capacity: document.getElementById('capacity'),
    leakRate: document.getElementById('leak_rate'),
    timeWindow: document.getElementById('time_window'),
    currentTime: document.getElementById('current_time')
};

let state = {
    lastLeakRate: 0,
    lastTimeWindow: 0,
    autoRequestInterval: null,
    isAutoRequestActive: false,
    isSending: false,
    isUpdatingState: false
};

const updateTime = () => {
    elements.currentTime.textContent = new Date().toLocaleTimeString('ru-RU');
};

const updateRoomHeight = (requests) => {
    const minHeight = 100;
    const circleHeight = 30;
    const basePadding = 40;
    const height = Math.max(minHeight, basePadding + Math.ceil(requests / 10) * circleHeight);
    const maxHeight = 400;
    const finalHeight = Math.min(height, maxHeight);
    elements.roomContainer.style.height = `${finalHeight}px`;
    elements.room.style.height = `${finalHeight}px`;
};

const addCircle = (index) => {
    const circle = document.createElement('div');
    circle.className = 'circle';
    const row = Math.floor(index / 10);
    const col = index % 10;
    const leftPosition = 50 + col * 30;
    const topPosition = 40 + row * 30;
    circle.style.left = '-30px';
    circle.style.top = `${topPosition}px`;
    elements.room.appendChild(circle);
    setTimeout(() => {
        circle.style.left = `${leftPosition}px`;
    }, 10);
    return circle;
};

const removeCircle = () => {
    const circles = elements.room.querySelectorAll('.circle:not(.removing)');
    if (circles.length === 0) return;

    const circle = circles[0];
    circle.classList.add('removing');
    circle.style.opacity = '0';
    setTimeout(() => {
        if (circle.parentNode) {
            elements.room.removeChild(circle);
        }
        const remainingCircles = elements.room.querySelectorAll('.circle:not(.removing)');
        remainingCircles.forEach((c, i) => {
            const row = Math.floor(i / 10);
            const col = i % 10;
            c.style.left = `${50 + col * 30}px`;
            c.style.top = `${40 + row * 30}px`;
        });
    }, 500);
};

const highlightCircles = (leakRate) => {
    const circles = elements.room.querySelectorAll('.circle:not(.removing)');
    circles.forEach((circle, index) => {
        circle.classList.toggle('highlight', index < leakRate);
    });
};

const syncCircles = (target, capacity, leakRate) => {
    const currentCircles = elements.room.querySelectorAll('.circle:not(.removing)').length;
    const newCount = Math.min(target, capacity);

    if (currentCircles < newCount) {
        for (let i = currentCircles; i < newCount; i++) {
            addCircle(i);
        }
    } else if (currentCircles > newCount) {
        for (let i = 0; i < currentCircles - newCount; i++) {
            removeCircle();
        }
    }

    updateRoomHeight(newCount);
    highlightCircles(leakRate);
};

const updateBucketDisplay = (bucket) => {
    elements.requests.textContent = bucket.requests;
    elements.capacity.textContent = bucket.capacity;
    elements.leakRate.textContent = bucket.leak_rate;
    elements.timeWindow.textContent = bucket.time_window;
};

const updateBucketState = async () => {
    if (state.isSending || state.isUpdatingState) return;

    state.isUpdatingState = true;
    try {
        const response = await axios.get('/leaky-bucket/state');
        const bucket = response.data;
        syncCircles(bucket.requests, bucket.capacity, bucket.leak_rate);
        updateBucketDisplay(bucket);
    } catch (error) {
        console.error('Ошибка при получении состояния:', error);
    } finally {
        state.isUpdatingState = false;
    }
};

const sendRequest = async () => {
    if (state.isSending) return;

    state.isSending = true;
    try {
        const response = await axios.post('/leaky-bucket/allow');
        const { allowed, bucket } = response.data;
        if (allowed) {
            syncCircles(bucket.requests, bucket.capacity, bucket.leak_rate);
        }
        updateBucketDisplay(bucket);
    } catch (error) {
        console.error('Ошибка при отправке запроса:', error);
    } finally {
        state.isSending = false;
    }
};

const updateAutoRequestInterval = () => {
    const frequency = parseFloat(elements.frequencySlider.value);
    elements.frequencyValue.textContent = frequency.toFixed(1);
    if (state.isAutoRequestActive) {
        clearInterval(state.autoRequestInterval);
        state.autoRequestInterval = setInterval(sendRequest, 1000 / frequency);
    }
};

const toggleAutoRequest = () => {
    state.isAutoRequestActive = !state.isAutoRequestActive;
    if (state.isAutoRequestActive) {
        updateAutoRequestInterval();
        elements.autoToggleButton.textContent = 'Выключить автоотправку';
        elements.autoToggleButton.classList.add('active');
    } else {
        clearInterval(state.autoRequestInterval);
        elements.autoToggleButton.textContent = 'Включить автоотправку';
        elements.autoToggleButton.classList.remove('active');
    }
};

window.sendRequest = sendRequest;
window.toggleAutoRequest = toggleAutoRequest;

elements.frequencySlider.addEventListener('input', updateAutoRequestInterval);

setInterval(updateTime, 1000);
setInterval(updateBucketState, 1000);

window.onload = () => {
    updateBucketState();
    updateTime();
    updateAutoRequestInterval();
};