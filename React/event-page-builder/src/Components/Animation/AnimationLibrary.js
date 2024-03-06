//////////////////////////////
// Animation Library
// Author: Joe Han
// Date: NOV 2022
// v.1.0.0

// Config
function animationLibrary() {
    // Default Values
    const defaultProp = "all";
    const defaultDuration = 0.7;
    const defaultType = "ease-in-out";
    const defaultDelay = 0;
    const defaultThreshold = 0.5;
    const defaultToggle = false;
    const defaultSequenceGap = 0.2;
    const defaultListener = "intersection";

    // HELPERS
    const renameCSS = (string) => {
        return (
            string.prop.split("-").slice(0, 1) +
            string.prop
                .split("-")
                .slice(1)
                .map((word) => word[0].toUpperCase() + word.substring(1))
                .join("")
        );
    };
    // Gets matching nodes and sets init value
    const setInitState = (nodes, styles) => {
        nodes.forEach((node) => {
            styles.forEach((style) => {
                const cssPropName =
                    style.prop.split("-").length < 2 ? style.prop : renameCSS(style);

                node.style[cssPropName] = style.init;
            });
        });
    };

    const generateTransitionValue = (el, animationObj) => {
        const {prop, duration, type, delay} = animationObj;
        if (!prop) return;
        // Determines each animation values
        // Custom values defined in HTML has the highest priority

        const finalDuration = (duration, el) => {
            if (duration || duration === 0) {
                return duration;
            }
            if (!el.dataset.animationSpeed && !duration && duration !== 0) {
                return defaultDuration;
            }
            if (el.dataset.animationSpeed) {
                return (duration || defaultDuration) / +el.dataset.animationSpeed;
            }
        };
        const finalType = (type, el) => {
            return type || el.dataset.animationType || defaultType;
        };
        const finalDelay = (delay, el) => {
            return (
                ((delay || 0) +
                    Number(el.dataset.animationDelay || 0) +
                    Number(el.dataset.animationSequence || 0)) *
                (el.dataset.animationSequenceGap || defaultSequenceGap)
            );
        };
        return {
            prop,
            duration: finalDuration(duration, el),
            type: finalType(type, el),
            delay: finalDelay(delay, el),
        };
    };

    const setTransition = (el, animationObjs) => {
        const transitionList = animationObjs.map((animationObj) => {
            const {prop, duration, type, delay} = generateTransitionValue(
                el,
                animationObj
            );

            return `${prop} ${duration}s ${type} ${delay}s`;
        });

        el.style.transition = `${defaultProp} ${defaultDuration}s ${defaultType} ${defaultDelay}s, ${transitionList.join(
            ", "
        )}`;
    };

    // LISTENERS
    const intersectionListener = (node, combinedAnimationProps) => {
        const {container, target, styles, threshold, toggle} =
            combinedAnimationProps;

        const overrideContainer = node.closest("[data-animation-container='true']");
        const containerNode = !overrideContainer ? node : overrideContainer;

        const animationThreshold = !overrideContainer
            ? threshold
            : +overrideContainer.dataset.animationThreshold || threshold;

        const trigger = new IntersectionObserver(
            ([entry]) => {
                const intersecting = entry.isIntersecting;

                const overrideTagets = overrideContainer
                    ? overrideContainer.querySelectorAll(target)
                    : [];

                // Animations with container listens for container element then apply animation to target element
                let animationTarget;
                if (overrideContainer) {
                    animationTarget = overrideTagets;
                }
                if (!overrideContainer && !container) animationTarget = [entry.target];
                if (!overrideContainer && container)
                    animationTarget = entry.target.querySelectorAll(target);

                animationTarget.forEach((node) => {
                    setTransition(node, styles);
                    styles.forEach(({prop, init, to}) => {
                        if (toggle) {
                            node.style[prop] = intersecting ? to : init;
                        } else {
                            node.style[prop] = intersecting && to;
                        }
                    });
                });
            },
            {threshold: animationThreshold}
        );
        trigger.observe(containerNode);
    };

    // CONTROLLER
    const animateController = (node, animationValues) => {
        const listener =
            node.dataset.animationListener ||
            animationValues.listener ||
            defaultListener;

        const combinedAnimationProps = {
            ...animationValues,
            threshold:
                +node.dataset.animationThreshold ||
                animationValues.threshold ||
                defaultThreshold,
            toggle: node.dataset.animationToggle || defaultToggle,
        };

        if (listener === "intersection")
            intersectionListener(node, combinedAnimationProps);
    };

    // INITIALIZER
    const observeAnimation = (animationValues) => {
        const {container, target, styles} = animationValues;
        const queryNodes = document.querySelectorAll(container || target);

        if (!container) {
            setInitState(queryNodes, styles);
        } else {
            queryNodes.forEach((el) => {
                setInitState(el.querySelectorAll(target), styles);
            });
        }

        queryNodes.forEach((node) => {
            animateController(node, animationValues);
        });
    };

    // Animation Objects
    const backgroundZoomIn = {
        target: ".animation__background-zoom-in",
        styles: [
            {
                prop: "background-size",
                init: "110%",
                to: "100%",
            },
        ],
    };
    const blur = {
        target: ".animation__blur",
        styles: [
            {
                prop: "filter",
                init: "blur(10px)",
                to: "blur(0)",
            },
        ],
    };
    const opacity = {
        target: ".animation__opacity-in",
        styles: [
            {
                prop: "opacity",
                init: 0,
                to: 1,
            },
        ],
    };
    const card = {
        target: ".animation__card",
        styles: [
            {
                prop: "opacity",
                init: 0,
                to: 1,
            },
            {
                prop: "transform",
                init: "translateY(-5em)",
                to: "translateY(0)",
            },
        ],
    };
    const textAppearDown = {
        container: ".animation__text-appear-down",
        target: ".animation__text-appear-down__target",
        styles: [
            {
                prop: "transform",
                init: "translateY(-150%)",
                to: "translateY(0)",
            },
            {
                prop: "opacity",
                init: 0,
                to: 1,
            },
        ],
    };
    const expandRight = {
        target: ".animation__expand-right",
        styles: [
            {
                prop: "width",
                init: "0%",
                to: "100%",
            },
        ],
    };

    // Call
    observeAnimation(backgroundZoomIn);
    observeAnimation(blur);
    observeAnimation(opacity);
    observeAnimation(card);
    observeAnimation(textAppearDown);
    observeAnimation(expandRight);
}

export default animationLibrary;
