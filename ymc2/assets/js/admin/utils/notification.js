/**
 * Notification
 * @param message
 * @param container
 * @param type
 * @param timeout
 */
export function notification(message, container = document.body, type = 'seccess', timeout = 3000) {
    const notification = document.createElement('div');
    notification.classList.add('notification', `notification--${type}`);
    notification.innerHTML = message;
    document.querySelector(container).appendChild(notification);
    setTimeout(() => notification.remove(), timeout);
}