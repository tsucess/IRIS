// import * as THREE from 'three';

// document.addEventListener('DOMContentLoaded', () => {
//     // Disable particles on mobile for performance
//     if (window.innerWidth < 768) return;

//     const scene = new THREE.Scene();
//     scene.background = new THREE.Color(0x000000);

//     const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
//     camera.position.z = 80;

//     const renderer = new THREE.WebGLRenderer({ antialias: true });
//     renderer.setSize(window.innerWidth, window.innerHeight);
//     renderer.domElement.style.position = 'fixed';
//     renderer.domElement.style.top = 0;
//     renderer.domElement.style.left = 0;
//     renderer.domElement.style.width = '100%';
//     renderer.domElement.style.height = '100%';
//     renderer.domElement.style.zIndex = '-1';
//     document.body.appendChild(renderer.domElement);

//     // === PARTICLES ===
//     const particleCount = 150;
//     const nodes = [];
//     const velocities = [];

//     for (let i = 0; i < particleCount; i++) {
//         const x = (Math.random() - 0.5) * 150;
//         const y = (Math.random() - 0.5) * 150;
//         const z = (Math.random() - 0.5) * 150;
//         nodes.push(new THREE.Vector3(x, y, z));

//         velocities.push(new THREE.Vector3(
//             (Math.random() - 0.5) * 0.15,
//             (Math.random() - 0.5) * 0.15,
//             (Math.random() - 0.5) * 0.15
//         ));
//     }

//     const particleGeometry = new THREE.BufferGeometry().setFromPoints(nodes);
//     const particleMaterial = new THREE.PointsMaterial({
//         color: 0x00ffff,
//         size: 0.7,
//         transparent: true,
//         opacity: 0.8,
//         blending: THREE.AdditiveBlending
//     });
//     const particles = new THREE.Points(particleGeometry, particleMaterial);
//     scene.add(particles);

//     // === CONNECTIONS ===
//     const maxDistance = 20;
//     const connectionMaterial = new THREE.LineBasicMaterial({
//         vertexColors: true,
//         transparent: true,
//         opacity: 0.6
//     });
//     const connectionGeometry = new THREE.BufferGeometry();
//     const connectionPositions = new Float32Array(particleCount * particleCount * 3 * 2);
//     const connectionColors = new Float32Array(particleCount * particleCount * 3 * 2);
//     const connectionMesh = new THREE.LineSegments(connectionGeometry, connectionMaterial);
//     scene.add(connectionMesh);

//     // === Mouse Rotation ===
//     let mouseX = 0, mouseY = 0;
//     const windowHalfX = window.innerWidth / 2;
//     const windowHalfY = window.innerHeight / 2;
//     document.addEventListener('mousemove', (event) => {
//         mouseX = (event.clientX - windowHalfX) / windowHalfX;
//         mouseY = -(event.clientY - windowHalfY) / windowHalfY;
//     });

//     // === Cinematic Camera Zoom ===
//     let zoomTime = 0;

//     function animate() {
//         requestAnimationFrame(animate);

//         // Breathing effect
//         zoomTime += 0.01;
//         camera.position.z = 80 + Math.sin(zoomTime) * 5;

//         // Rotate scene slightly with mouse
//         scene.rotation.y += (mouseX * 0.2 - scene.rotation.y) * 0.02;
//         scene.rotation.x += (mouseY * 0.2 - scene.rotation.x) * 0.02;

//         // Move nodes
//         for (let i = 0; i < particleCount; i++) {
//             nodes[i].add(velocities[i]);

//             if (nodes[i].x > 75 || nodes[i].x < -75) velocities[i].x *= -1;
//             if (nodes[i].y > 75 || nodes[i].y < -75) velocities[i].y *= -1;
//             if (nodes[i].z > 75 || nodes[i].z < -75) velocities[i].z *= -1;

//             if (Math.random() < 0.001) {
//                 velocities[i].x += (Math.random() - 0.5) * 0.1;
//                 velocities[i].y += (Math.random() - 0.5) * 0.1;
//                 velocities[i].z += (Math.random() - 0.5) * 0.1;
//             }
//         }

//         particleGeometry.setFromPoints(nodes);

//         // Build dynamic connections with smooth fade
//         let index = 0;
//         let colorIndex = 0;
//         for (let i = 0; i < particleCount; i++) {
//             for (let j = i + 1; j < particleCount; j++) {
//                 const dist = nodes[i].distanceTo(nodes[j]);
//                 if (dist < maxDistance) {
//                     connectionPositions[index++] = nodes[i].x;
//                     connectionPositions[index++] = nodes[i].y;
//                     connectionPositions[index++] = nodes[i].z;
//                     connectionPositions[index++] = nodes[j].x;
//                     connectionPositions[index++] = nodes[j].y;
//                     connectionPositions[index++] = nodes[j].z;

//                     const fade = 1 - dist / maxDistance;
//                     for (let k = 0; k < 2; k++) {
//                         connectionColors[colorIndex++] = 0.0;
//                         connectionColors[colorIndex++] = fade;
//                         connectionColors[colorIndex++] = 1.0;
//                     }
//                 }
//             }
//         }
//         connectionGeometry.setDrawRange(0, index / 3);
//         connectionGeometry.setAttribute('position', new THREE.BufferAttribute(connectionPositions, 3).setUsage(THREE.DynamicDrawUsage));
//         connectionGeometry.setAttribute('color', new THREE.BufferAttribute(connectionColors, 3).setUsage(THREE.DynamicDrawUsage));

//         renderer.render(scene, camera);
//     }
//     animate();

//     window.addEventListener('resize', () => {
//         camera.aspect = window.innerWidth / window.innerHeight;
//         camera.updateProjectionMatrix();
//         renderer.setSize(window.innerWidth, window.innerHeight);
//     });
// });


import * as THREE from 'three';

document.addEventListener('DOMContentLoaded', () => {
    // Disable particles on mobile and tablets for performance
    if (window.innerWidth < 1024) return;

    // Only load particles on homepage and dashboard
    const allowedPages = ['/', '/dashboard', '/welcome'];
    if (!allowedPages.some(page => window.location.pathname.includes(page) || window.location.pathname === '/')) {
        return;
    }

    const container = document.getElementById('particles-background');
    if (!container) return;

    // === Scene Setup ===
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x000000);

    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 80;

    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    container.style.position = 'fixed';
    container.style.top = 0;
    container.style.left = 0;
    container.style.width = '100%';
    container.style.height = '100%';
    container.style.zIndex = '-1';
    container.appendChild(renderer.domElement);

    // === Particles === (Reduced count for better performance)
    const particleCount = 80; // Reduced from 150
    const nodes = [];
    const velocities = [];

    for (let i = 0; i < particleCount; i++) {
        const x = (Math.random() - 0.5) * 150;
        const y = (Math.random() - 0.5) * 150;
        const z = (Math.random() - 0.5) * 150;
        nodes.push(new THREE.Vector3(x, y, z));
        velocities.push(new THREE.Vector3(
            (Math.random() - 0.5) * 0.15,
            (Math.random() - 0.5) * 0.15,
            (Math.random() - 0.5) * 0.15
        ));
    }

    const particleGeometry = new THREE.BufferGeometry().setFromPoints(nodes);
    const particleMaterial = new THREE.PointsMaterial({
        color: 0x00ffff,
        size: 0.7,
        transparent: true,
        opacity: 0.8,
        blending: THREE.AdditiveBlending
    });
    const particles = new THREE.Points(particleGeometry, particleMaterial);
    scene.add(particles);

    // === Connections ===
    const maxDistance = 20;
    const connectionMaterial = new THREE.LineBasicMaterial({
        vertexColors: true,
        transparent: true,
        opacity: 0.6
    });
    const connectionGeometry = new THREE.BufferGeometry();
    const connectionPositions = new Float32Array(particleCount * particleCount * 3 * 2);
    const connectionColors = new Float32Array(particleCount * particleCount * 3 * 2);
    const connectionMesh = new THREE.LineSegments(connectionGeometry, connectionMaterial);
    scene.add(connectionMesh);

    // === Mouse Interaction ===
    let mouseX = 0, mouseY = 0;
    const windowHalfX = window.innerWidth / 2;
    const windowHalfY = window.innerHeight / 2;

    document.addEventListener('mousemove', (event) => {
        mouseX = (event.clientX - windowHalfX) / windowHalfX;
        mouseY = -(event.clientY - windowHalfY) / windowHalfY;
    });

    // === Camera Breathing Zoom ===
    let zoomTime = 0;

    // === Animation Loop Control ===
    let isActive = true;
    document.addEventListener("visibilitychange", () => {
        isActive = !document.hidden;
    });

    function animate() {
        if (!isActive) {
            requestAnimationFrame(animate);
            return;
        }

        requestAnimationFrame(animate);

        // Cinematic breathing zoom
        zoomTime += 0.01;
        camera.position.z = 80 + Math.sin(zoomTime) * 5;

        // Rotate scene with mouse
        scene.rotation.y += (mouseX * 0.2 - scene.rotation.y) * 0.02;
        scene.rotation.x += (mouseY * 0.2 - scene.rotation.x) * 0.02;

        // Particle motion
        for (let i = 0; i < particleCount; i++) {
            nodes[i].add(velocities[i]);

            if (nodes[i].x > 75 || nodes[i].x < -75) velocities[i].x *= -1;
            if (nodes[i].y > 75 || nodes[i].y < -75) velocities[i].y *= -1;
            if (nodes[i].z > 75 || nodes[i].z < -75) velocities[i].z *= -1;

            // Random jitter to prevent uniform motion
            if (Math.random() < 0.001) {
                velocities[i].x += (Math.random() - 0.5) * 0.1;
                velocities[i].y += (Math.random() - 0.5) * 0.1;
                velocities[i].z += (Math.random() - 0.5) * 0.1;
            }
        }
        particleGeometry.setFromPoints(nodes);

        // Connections with fade effect
        let index = 0;
        let colorIndex = 0;
        for (let i = 0; i < particleCount; i++) {
            for (let j = i + 1; j < particleCount; j++) {
                const dist = nodes[i].distanceTo(nodes[j]);
                if (dist < maxDistance) {
                    connectionPositions[index++] = nodes[i].x;
                    connectionPositions[index++] = nodes[i].y;
                    connectionPositions[index++] = nodes[i].z;
                    connectionPositions[index++] = nodes[j].x;
                    connectionPositions[index++] = nodes[j].y;
                    connectionPositions[index++] = nodes[j].z;

                    const fade = 1 - dist / maxDistance;
                    for (let k = 0; k < 2; k++) {
                        connectionColors[colorIndex++] = 0.0;
                        connectionColors[colorIndex++] = fade;
                        connectionColors[colorIndex++] = 1.0;
                    }
                }
            }
        }
        connectionGeometry.setDrawRange(0, index / 3);
        connectionGeometry.setAttribute('position', new THREE.BufferAttribute(connectionPositions, 3).setUsage(THREE.DynamicDrawUsage));
        connectionGeometry.setAttribute('color', new THREE.BufferAttribute(connectionColors, 3).setUsage(THREE.DynamicDrawUsage));

        renderer.render(scene, camera);
    }
    animate();

    // === Handle Window Resize ===
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
});
